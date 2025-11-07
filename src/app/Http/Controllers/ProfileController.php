<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;

use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;

use Illuminate\Support\Facades\Auth; //認証

class ProfileController extends Controller
{
    public function myPage(Request $request)
    {
        $user = Auth::user();

        $page =$request->query('page','sell');

        $myProducts = Product::where('user_id', $user->id)->get();

        $purchases = Purchase::where('user_id', $user->id)
                        ->with('product') // 商品情報も同時に取得
                        ->get();

        return view('mypage', compact('user', 'page', 'myProducts', 'purchases'));
    }

    public function editProfile()
    {
        $user = Auth::user();

        return view('profile', compact('user'));
    }

    public function updateProfile(ProfileRequest $request)
    {
        $user = Auth::user();

        $data = $request->only(['name','postcode','address','building']);

        if ($request->hasFile('img')) {
            if ($user->img_url && \Storage::disk('public')->exists($user->img_url)) {
                \Storage::disk('public')->delete($user->img_url);
            }

            $path = $request->file('img')->store('user_images', 'public');
            $data['img_url'] = $path;
        }

        $isFirstProfile = empty($user->postcode) && empty($user->address);

        $user->update($data);

        if ($isFirstProfile) {
        return redirect()->route('top.show');
        }

        return redirect()->route('mypage.show');
    }
}
