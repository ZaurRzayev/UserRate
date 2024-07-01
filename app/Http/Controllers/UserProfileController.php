<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Add this line
use App\Models\User;

class UserProfileController extends Controller
{
    public function updatePercentage(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'bracket_string' => 'required|string',
        ]);

        $userId = $request->get('user_id');
        $bracketString = $request->get('bracket_string');

        // Calculate the percentage of filled brackets
        $totalBrackets = 10;
        $filledBrackets = substr_count($bracketString, '{something}');
        $percentage = ($filledBrackets / $totalBrackets) * 100;

        // Update the Percentage in Adalo
        $client = new Client();
        $response = $client->put('https://api.adalo.com/v0/apps/0fb25ec4-853d-487d-a48e-bb871341619a/collections/t_66ad570ab2cf4e91b74569e7becda694' . $userId, [
            'json' => [
                'Percentage' => $percentage,
            ],
            'headers' => [
                'Authorization' => 'Bearer 5ckiny17el2vymy81icxgnsbu"',
            ],
        ]);

        if ($response->getStatusCode() == 200) {
            return response()->json(['error' => false, 'percentage' => $percentage]);
        } else {
            return response()->json(['error' => true, 'message' => 'Failed to update Percentage']);
        }
    }







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

        return response()->json([$completionRate]);
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
