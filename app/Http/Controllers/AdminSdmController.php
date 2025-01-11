<?php

namespace App\Http\Controllers;

class AdminSdmController extends Controller
{
    //
    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard'
        ];

        return view('admin_sdm.dashboard', $data);
    }

}
