<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->is_admin) {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome to Admin Panel!');
            } else {
                Auth::logout();
                return back()->with('error', 'You do not have admin privileges.');
            }
        }

        return back()->with('error', 'Invalid credentials.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }

    public function dashboard()
    {
        // Get counts for dashboard cards
        $stats = [
            'leagues' => \App\Models\League::count(),
            'teams' => \App\Models\Team::count(),
            'players' => \App\Models\Player::count(),
            'matches' => \App\Models\FootballMatch::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
