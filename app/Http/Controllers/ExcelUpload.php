<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelUpload extends Controller
{
    public function fileupload()
    {
        return view('upload');
    }
    public function import(Request $request)
    {

        $request->validate([
            'file'=>'required|mimes:xlsx,xls,csv'
        ]);
        Excel::import(new UsersImport,$request->file('file'));
        if (!empty($import->failedRows)) {
            return back()->with('error', 'Some rows failed to import.')->with('failedRows', $import->failedRows);
        }
        return back()->with('success', 'Users imported successfully!');

    }
}
