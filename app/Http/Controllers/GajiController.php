<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GajiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roleSlug = Auth::user()->role->slug;
        $users = User::active()->notAdminRole()->get();
        $gajis = Gaji::get();
        //dd(Auth::user()->role->slug);
        
        $data = [
            'title' => 'Data Gaji Karyawan',
            'users' => $users,
            'gajis' => $gajis
        ];

        // return view('admin.sub_jabatan', $data);
        if($roleSlug == 'admin-sdm'){
            return view('admin_sdm.gaji', $data);
        }else{
            return redirect()->back()->with('error', 'Anda Tidak Memiliki Akses ke Halaman Ini');
        }
    }
}
