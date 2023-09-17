<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\LinkDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function signIn()
    {
        return Inertia::render('Auth/SignIn');
    }

    public function signUp()
    {
        return Inertia::render('Auth/SignUp');
    }

    public function profile()
    {
        $user = User::where('id', auth()->id())->with('link.linkDetails')->first();

        // Check if user has no link
        if ($user->link == null) {
            // Create a simple link and link detail
            $link = Link::create([
                'user_id' => auth()->id(),
            ]);

            LinkDetail::create([
                'link_id' => $link->id,
                'url'     => config('app.url'),
                'link'    => config('app.url'),
            ]);

            // Reload the user to get the newly created link and link details
            $user = User::where('id', auth()->id())->with('link.linkDetails')->first();
        }

        return Inertia::render('Profile/Index', [
            'account' => $user,
        ]);
    }

    public function uploadProfile(Request $request)
    {
        $request->validate([
            'profile' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $user = Auth::user();

        if ($request->hasFile('profile')) {
            $path = $request->file('profile')->store('profiles', 'public');

            // Delete old image
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $user->update([
                'profile_picture' => $path,
            ]);
        }

        return redirect()->back()->with('success', 'Profile picture updated successfully');
    }

    public function uploadCover(Request $request)
    {
        $request->validate([
            'cover' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $user = Auth::user();

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('cover', 'public');

            // Delete old image
            if ($user->cover) {
                Storage::disk('public')->delete($user->cover);
            }

            $user->update([
                'cover' => $path,
            ]);
        }

        return redirect()->back()->with('success', 'Cover picture updated successfully');
    }
}
