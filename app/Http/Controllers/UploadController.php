<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function showUploadForm()
    {
        return view('upload');
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('image')) {
            $uploadedImage = $request->file('image')->store('images', 'public');
            return view('upload', ['uploadedImage' => asset('storage/' . $uploadedImage)]);
        }

        return view('upload');
    }
}
