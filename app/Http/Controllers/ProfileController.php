<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('attendance.dashboard'), 'icon' => 'house-fill'],
            ['label' => 'Profile', 'icon' => 'person-circle']
        ];
        
        return view('profile.show', compact('user', 'breadcrumbs'));
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        $user = Auth::user();

        // Delete old profile picture if exists
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Store new profile picture
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = 'profile_pictures/' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('public')->putFileAs('', $file, $filename);
            
            $user->update(['profile_picture' => $path]);
        }

        return redirect()->route('profile.show')->with('success', 'Profile picture updated successfully!');
    }

    public function editProfile()
    {
        $user = Auth::user();
        
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('attendance.dashboard'), 'icon' => 'house-fill'],
            ['label' => 'Profile', 'url' => route('profile.show'), 'icon' => 'person-circle'],
            ['label' => 'Edit', 'icon' => 'pencil-square']
        ];
        
        return view('profile.edit', compact('user', 'breadcrumbs'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->update($request->only('name', 'email'));

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }
}
