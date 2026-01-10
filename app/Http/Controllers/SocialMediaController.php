<?php

namespace App\Http\Controllers;

use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocialMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $getSocialMedia = SocialMedia::where('user_id', Auth::id())->get();
        $data = [
            'title' => 'Sosial Media',
            'social_media' => $getSocialMedia
        ];

        return view('karyawan.social_media', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = [
            'user_id' => $request->user_id ? $request->user_id : Auth::id(),
            'nama_social_media' => $request->nama_social_media,
            'link' => $request->link
        ];

        SocialMedia::create($data);

        return redirect()->back()->with('success', 'Sosial media berhasil di tambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $getSocialMedia = SocialMedia::findOrFail($id);
        $data = [
            'nama_social_media' => $request->nama_social_media,
            'link' => $request->link
        ];

        $getSocialMedia->update($data);

        return redirect()->back()->with('success', 'Sosial media berhasil di update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $getSocialMedia = SocialMedia::findOrFail($id);

        $getSocialMedia->delete();

        return redirect()->back()->with('success', 'Sosial media berhasil di hapus');
    }
}
