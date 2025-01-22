<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KaryawanController extends Controller
{

    public function index(){
        return view('home');
    }

    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard'
        ];

        return view('karyawan.dashboard', $data);
    }
}
