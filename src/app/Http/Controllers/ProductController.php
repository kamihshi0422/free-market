<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ExhibitionRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Condition;

use Illuminate\Support\Facades\Auth; //認証

class ProductController extends Controller
{
    public function top(Request $request)
    {
         // クエリビルダーを作成
        $query = Product::query()->with('purchase')->withCount('mylists'); // 購入情報もロード

        // 自分が出品した商品は除外
        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }

         // 商品名で部分一致検索
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('name', 'like', "%{$keyword}%");
        }

        // マイリストタブの場合
        if ($request->tab === 'mylist') {
            if (Auth::check()) {
                $query->whereIn('id', Auth::user()->mylists()->pluck('products.id'));
            } else {
                // 未認証は0件
                $query->whereRaw('0=1');
            }
        }

        $products = $query->get();

        return view('top',compact('products'));
    }

    public function like($id)
    {
        $user = Auth::user();
        $product = Product::withCount('mylists')->findOrFail($id);

        // いいねの追加・削除
        if ($user->mylists()->where('product_id', $id)->exists()) {
            $user->mylists()->detach($id);
        } else {
            $user->mylists()->attach($id);
        }

        // ユーザーの mylists を再ロード
        $user->load('mylists');

         // リダイレクトして detail() を呼び、最新データを取得
        return redirect()->route('detail.show', $id);
    }

    public function detail($id)
    {
        $product = Product::with(['categories', 'condition', 'comments', 'mylists', 'purchase'])
                        ->withCount('mylists')
                        ->findOrFail($id);

        // ログイン済みならユーザーの mylists も最新化
        if (Auth::check()) {
            Auth::user()->load('mylists');
        }

        return view('detail', compact('product'));
    }

    public function comment(CommentRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $request->content,
        ]);

        return redirect()->route('detail.show',$id);
    }

    // 購入画面表示
    public function showBuy(Request $request, $id)
    {
        $user = Auth::user();
        $product = Product::findOrFail($id);

        // 変更済み住所があるか確認
        $address = \App\Models\Address::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->first();

        // なければユーザーの登録住所を使う
        if (!$address) {
            $address = (object)[
                'postcode' => $user->postcode,
                'address'  => $user->address,
                'building' => $user->building,
            ];
        }

        $pay_method = $request->input('pay_method', old('pay_method', null));

        return view('buy',compact('product','user','address', 'pay_method'));
    }

    public function buy(PurchaseRequest $request, $id)
    {
        $user = Auth::user();
        $product = Product::findOrFail($id);
        $pay_method = $request->input('pay_method', 1); // 1: コンビニ, 2: カード

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $payment_method_types = $pay_method == 1 ? ['konbini'] : ['card'];

        // Stripeセッション作成
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => $payment_method_types,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $product->name],
                    'unit_amount' => $product->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('top.show') . '?session_id={CHECKOUT_SESSION_ID}', // 成功後トップへ
            'cancel_url' => route('top.show'),
            'metadata' => [
                'product_id' => $product->id,
                'user_id' => $user->id,
            ],
        ]);

        // ✅ DBに仮購入を登録（Stripe決済前の状態）
        \App\Models\Purchase::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'pay_method' => $pay_method,
            'address' => $request->input('address'),
            'stripe_payment_intent_id' => null, // 決済前
        ]);

        // StripeページURLを返す
        return redirect($session->url);
    }

    public function editAddress($id)
    {
        $user = Auth::user();
        $product = Product::findOrFail($id);

        // 既存住所を取得、なければユーザー基本住所を設定
        $address = \App\Models\Address::firstOrNew(
            ['user_id' => $user->id, 'product_id' => $product->id],
            [
                'postcode' => $user->postcode,
                'address'  => $user->address,
                'building' => $user->building,
            ]
        );

        return view('address', compact('product', 'address'));
    }

    public function updateAddress(AddressRequest $request, $id)
    {
        $user = Auth::user();

        $validated = $request->validated();

        \App\Models\Address::updateOrCreate(
            ['user_id' => $user->id, 'product_id' => $id],
            [
                'postcode' => $validated['postcode'],
                'address'  => $validated['address'],
                'building' => $validated['building'] ?? null,
            ]
        );

        return redirect()->route('purchases.show', $id);
    }

    public function showSell()
    {
        $categories = Category::all();
        $conditions = Condition::all();


        return view('sell',compact('categories','conditions'));
    }

    public function sell(ExhibitionRequest $request)
    {
        $data = $request->validated();

        $path = $request->file('img_url')->store('products_images', 'public');

        $product = Product::create([
            'user_id' => Auth::id(),
            'name' => $data['name'],
            'brand_name' => $data['brand_name'] ?? null,
            'detail' => $data['detail'],
            'price' => $data['price'],
            'condition_id' => $data['condition_id'],
            'img_url' => $path,
        ]);

        $product->categories()->sync($data['category_ids']);

        return redirect()->route('top.show');
    }
}