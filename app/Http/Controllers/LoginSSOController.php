<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LoginSSOController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;
        $title = 'Sync SSO';
        
        //$url = 'http://localhost/loginsso/api/getUserApp/' . $userId . '/sdm';
        //$url = 'https://loginsso.chaakra-consulting.com/api/getUserApp/' . $userId . '/sdm';
        $url = 'https://loginsso.chaakra-consulting.com/api/AppController/getUserApp/' . $userId . '/sdm';
        $response = Http::get($url);

        $ssoData = json_decode($response);

        return view('sso', compact('title','ssoData'));
    }

    public function storeSSO(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //$response = Http::asForm()->post('http://localhost/loginsso/api/UserController/cek_login', $validated);
        $response = Http::asForm()->post('https://loginsso.chaakra-consulting.com/api/UserController/cek_login', $validated);

        $ssoData = json_decode($response);

        if ($ssoData->success == false) {
            return redirect()->back()->with('error', 'Akun SSO tidak ada.');
        } else {
            $url = 'https://loginsso.chaakra-consulting.com/api/AppController/createUserApp';
            //$url = 'http://localhost/loginsso/api/AppController/createUserApp';

            $response = Http::asForm()->post($url, [
                'user_id' => $ssoData->data_user->id,
                'app_key' => 'sdm',
                'user_app_id' => $user->id,
                'role' => $user->role_id,
                'redirect_url' => '-'
            ]);

            $getResponCreate = json_decode($response);

            if ($getResponCreate->success) {
                return redirect()->back()->with('success', 'Akun SSO Berhasil Di Sync');
            } else {
                return redirect()->back()->with('error', 'Akun SSO Gagal Di Sync');
            }
        }
    }

    /**
     * Login SSO user.
     */
    public function loginSSO(Request $request)
    {
        $validated = $request->validate([
            'user_app_id' => 'required',
            'role' => 'required',
        ]);
        $validated['app_key'] = 'sdm';

        $user = User::find($validated['user_app_id']);
        if(!$user) return redirect()->back()->withInput()->with('error', 'Akun tidak terdaftar');
        if(Auth::loginUsingId($user->id)){
            if($user->role_id == 1){
                return redirect()->route('home')->with('success', 'Login successful.');
            } elseif ($user->role_id == 2){
                return redirect()->route('home')->with('success', 'Login successful.');
            } elseif ($user->role_id == 3){
                return redirect()->route('karyawan.dashboard')->with('success', 'Login successful.');
            } elseif($user->role_id == 4){
                return redirect()->route('admin_sdm.dashboard')->with('success', 'Login successful');
            } elseif($user->role_id == 5){
                return redirect()->route('home')->with('success', 'Login successful');
            } elseif($user->role_id == 6){
                return redirect()->route('manajer.dashboard')->with('success', 'Login successful');
            }
        }

        // // Jika kredensial tidak valid, tampilkan pesan error
        // return back()->withErrors([
        //     'email' => 'The provided credentials do not match our records.',
        // ]);
    }

        /**
     * Login SSO user.
     */
    public function loginSSOForm(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $validated['app_key'] = 'sdm';
        $response = Http::asForm()->post('http://localhost/loginsso/api/UserController/cek_login', $validated);
        //$response = Http::asForm()->post('https://loginsso.chaakra-consulting.com/api/UserController/cek_login', $validated);

        $ssoData = json_decode($response);

        if ($ssoData->success == true) {
            $user = User::find($ssoData->data_app->user_app_id);
            if(!$user) return redirect()->back()->withInput()->with('error', 'Akun tidak terdaftar');
            
            if(Auth::loginUsingId($user->id)){
                if($user->role_id == 1){
                    return redirect()->route('home')->with('success', 'Login successful.');
                } elseif ($user->role_id == 2){
                    return redirect()->route('home')->with('success', 'Login successful.');
                } elseif ($user->role_id == 3){
                    return redirect()->route('karyawan.dashboard')->with('success', 'Login successful.');
                } elseif($user->role_id == 4){
                    return redirect()->route('admin_sdm.dashboard')->with('success', 'Login successful');
                } elseif($user->role_id == 5){
                    return redirect()->route('home')->with('success', 'Login successful');
                } elseif($user->role_id == 6){
                    return redirect()->route('manajer.dashboard')->with('success', 'Login successful');
                }
            }else{
                return redirect()->back()->withInput()->with('error', 'The provided credentials do not match our records.');
            }
        }

        // Jika kredensial tidak valid, tampilkan pesan error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
