<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;

class RoleController extends Controller
{
    public function create()
    {
        $roles = Role::all();
        $title = 'Role';
        return view('admin.roles', compact('roles', 'title'));
    }

    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'name' => 'required|unique:roles,name|max:255',
        ]);

        // Menyimpan role baru
        $role = new Role();
        $role->name = $request->name;
        $role->save();

        // Redirect atau kembalikan response
        return redirect()->back()->with('success', 'Role successfully added!');
    }

    public function update(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id . '|max:255',
        ]);

        // Cari role berdasarkan ID
        $role = Role::findOrFail($id);

        // Update nama role
        $role->name = $request->name;
        $role->save();

        // Redirect atau kembalikan response
        return redirect()->back()->with('success', 'Role successfully updated!');
    }

    // Fungsi untuk menghapus role
    public function destroy($id)
    {
        // Cari role berdasarkan ID dan hapus
        $role = Role::findOrFail($id);

        $cekUsers = User::where('role_id', $id)->first();

        if($cekUsers){
            return redirect()->back()->with('error', 'Role gagal di hapus terdapat data pada role ini!'); 
        }

        $role->delete();

        // Redirect atau kembalikan response
        return redirect()->back()->with('success', 'Role successfully deleted!');
    }
}

