<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;

use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;

use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function showProfile(Request $request)
    {
        $user = Auth::user();
        $page = $request->query('page','sell');
        $myProducts = Product::where('user_id', $user->id)->get();
        $purchases = Purchase::where('user_id', $user->id)->with('product')->get();

        return view('profile', compact('user', 'page', 'myProducts', 'purchases'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        $page = null;

        return view('edit_profile', compact('user','page'));
    }

    public function updateProfile(ProfileRequest $request)
    {
        $user = Auth::user();
        $data = $request->only(['name','postcode','address','building']);

        if ($request->hasFile('img')) {
            if ($user->img_url && \Storage::disk('public')->exists($user->img_url)) {
                \Storage::disk('public')->delete($user->img_url);
            }

            $image = $request->file('img')->store('user_images', 'public');
            $data['img_url'] = $image;
        }

        $isFirstProfile = empty($user->postcode) && empty($user->address);
        $user->update($data);

        if ($isFirstProfile) {
            return redirect()->route('top.show');
        }

        return redirect()->route('profile.show');
    }
}