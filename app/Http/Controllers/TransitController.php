<?php

namespace App\Http\Controllers;

use App\Imports\TransitImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TransitController extends Controller
{
    public function index()
    {
        return view('transit.index');
    }

    public function upload(Request $request)
    {
        // validate
        // $request->validate([
        //     'file_upload' => 'required|mimes:xlsx,xls',
        // ]);


        // get file
        $file = $request->file('file_upload');

        // give a unique name to the file
        $file_name = rand() . '_' . $file->getClientOriginalName();

        // move the file to the storage
        $file->move('public/file_upload', $file_name);

        // import data
        Excel::import(new TransitImport, public_path('/file_upload/' . $file_name));

        // return response
        return "upload success";
    }
}
