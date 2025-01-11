<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index(){
        return view('auth.login');
    }
    
    public function register()
    {
        return view('auth.register');
    }

    public function register_process(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->back()->with('success', 'Registrasi berhasil! Anda dapat login sekarang.')->header('Refresh', '3;url=' . route('login'));
    }

    /**
     * Login user.
     */
    public function login(Request $request)
    {
        // Validasi kredensial login
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek apakah kredensial valid dan login berhasil
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if($user->role_id == 1){
                return redirect()->route('home')->with('success', 'Login successful.');
            } elseif ($user->role_id == 2){
                return redirect()->route('home')->with('success', 'Login successful.');
            } elseif ($user->role_id == 3){
                return redirect()->route('karyawan.dashboard')->with('success', 'Login successful.');
            } elseif($user->role_id == 4){
                return redirect()->route('admin_sdm.dashboard')->with('success', 'Login successful');
            }
        }

        // Jika kredensial tidak valid, tampilkan pesan error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        return redirect()->route('login')->with('success', 'Logout successful.');
    }
}
