<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;

class FileController extends Controller
{
    public function upload(UploadFileRequest  $request)
    {
        dd($request->validated()['file_data']->store('avatars'));
    }
}
