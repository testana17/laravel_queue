<?php

// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Jobs\SendWelcomeEmail;

class UsersController extends Controller
{
    // Method for storing a new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Dispatch the job to send welcome email
        SendWelcomeEmail::dispatch($user);

        return response()->json(['message' => 'User created and email will be sent shortly'], 201);
    }

    // Method for displaying the list of users
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Method for showing a specific user
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // Method for updating a user
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
        ]);

        $user = User::findOrFail($id);
        $user->update($request->all());

        return response()->json(['message' => 'User updated successfully'], 200);
    }

    // Method for deleting a user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
