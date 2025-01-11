<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Role;
use App\Models\SubJabatan;
use Illuminate\Http\Request;

class UserController extends Controller
{


    public function index()
    {
        // Ambil semua data pengguna
        $title = 'List User';
        $users = User::with('role')->get();
        $roles = Role::all();

        // Kirim data pengguna ke view
        return view('admin.index', compact('users', 'roles','title'));
    }


    public function updateRole(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'role_id' => 'required|exists:roles,id', // Pastikan role_id valid
        ]);
        
        $getRole = Role::where('id', $request->role_id)->first();

        if($getRole->name == 'Admin' || $getRole->name ==  'Super Admin'){

            $cek_pengguna = User::where('role_id', $request->role_id)->first();

            if($cek_pengguna){
                return redirect()->back()->with('error', 'Pemilihan role [' . $getRole->name .'] hanya bisa diisi oleh satu user');
            }
        }

        // Cari user berdasarkan ID
        $user = User::findOrFail($id);

        // Update role pada user
        $user->role_id = $request->role_id;
        $user->save();

        // Redirect kembali ke halaman users
        return redirect()->back()->with('success', 'Role updated successfully!');
    }

    public function updateSubJabatan(Request $request, $id)
    {
        $request->validate([
            'sub_jabatan_id' => 'required|exists:sub_jabatans,id', // Pastikan role_id valid
        ]);

        // Cari user berdasarkan ID
        $user = User::findOrFail($id);

        // Update role pada user
        $user->sub_jabatan_id = $request->sub_jabatan_id;
        $user->save();

        // Redirect kembali ke halaman users
        return redirect()->back()->with('success', 'Sub jabatan berhasil di update!');
    }

}
