<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserProfileController extends Controller
{


    public function calculateCompletionRate(User $user)
    {
        $fields = ['name', 'email', 'dob', 'city', 'country', 'phone', 'bio', 'profession'];
        $completedFields = 0;

        foreach ($fields as $field) {
            if (!empty($user->$field)) {
                $completedFields++;
            }
        }

        return ($completedFields / count($fields)) * 100;
    }

    public function getProfileCompletion(User $user)
    {
        $completionRate = $this->calculateCompletionRate($user);
        return response()->json(['completionRate' => $completionRate]);
    }


    // Method to calculate the completion rate
//    public function calculateCompletionRate(User $user)
//    {
//        $fields = ['name', 'email', 'dob', 'city', 'country', 'phone', 'bio', 'profession'];
//        $completedFields = 0;
//
//        foreach ($fields as $field) {
//            if (!empty($user->$field)) {
//                $completedFields++;
//            }
//        }
//
//        return ($completedFields / count($fields)) * 100;
//    }

    // Method to show the profile page
    public function show()
    {
        /** @var User $user */
        $user = Auth::user();
        $completionRate = $this->calculateCompletionRate($user);

        return view('profile.show', compact('user', 'completionRate'));
    }

    // Method to show the edit profile form
    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();
        $completionPercentage = $this->calculateCompletionRate($user);

        return view('profile_edit', compact('user', 'completionPercentage'));
    }

//     Method to update the profile
//     Method to update the profile
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
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}


    // Method to update the profile
//    public function update(Request $request, $id)
//    {
//        $request->validate([
//            'name' => 'required|string|max:255',
//            'email' => 'required|string|email|max:255',
//            'dob' => 'nullable|date',
//            'city' => 'nullable|string|max:255',
//            'country' => 'nullable|string|max:255',
//            'phone' => 'nullable|string|max:20',
//            'bio' => 'nullable|string|max:1000',
//            'profession' => 'nullable|string|max:255',
//        ]);
//
//        $user = Auth::user();
//        $user->name = $request->input('name');
//        $user->email = $request->input('email');
//        $user->dob = $request->input('dob');
//        $user->city = $request->input('city');
//        $user->country = $request->input('country');
//        $user->phone = $request->input('phone');
//        $user->bio = $request->input('bio');
//        $user->profession = $request->input('profession');
//        $user->save();
//
//        return redirect()->back()->with('success', 'Profile updated successfully.');
//    }



//}
