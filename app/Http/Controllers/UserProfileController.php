<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Add this line
use App\Models\User;

class UserProfileController extends Controller
{
    // Method to calculate the completion rate
    private function calculateCompletionRate(User $user)
    {
        $fields = ['name', 'email', 'dob', 'city', 'country', 'phone', 'bio', 'profession'];
        $filledFields = 0;

        foreach ($fields as $field) {
            if (!empty($user->$field)) {
                $filledFields++;
            }
        }

        $totalFields = count($fields);
        return ($totalFields > 0) ? ($filledFields / $totalFields) * 100 : 0;
    }

    // Method to get completion rate for a user by ID
    public function getCompletionRate($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $completionRate = $this->calculateCompletionRate($user);

        return response()->json(['completion_rate' => $completionRate]);
    }

    // Method to show the profile page
    public function show()
    {
        $user = Auth::user();
        $completionRate = $this->calculateCompletionRate($user);

        return view('profile.show', compact('user', 'completionRate'));
    }

    // Method to show the edit profile form
    public function edit()
    {
        $user = Auth::user();
        $completionRate = $this->calculateCompletionRate($user);

        return view('profile_edit', compact('user', 'completionRate'));
    }

    // Method to update the profile
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'dob' => 'nullable|date',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'profession' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->dob = $request->input('dob');
        $user->city = $request->input('city');
        $user->country = $request->input('country');
        $user->phone = $request->input('phone');
        $user->bio = $request->input('bio');
        $user->profession = $request->input('profession');

        // Calculate completion rate
        $completionRate = $this->calculateCompletionRate($user);

        // Store completion rate in the database
        $user->completion_rate = $completionRate;
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
