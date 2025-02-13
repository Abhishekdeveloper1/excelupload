<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserImportNotification;
class ExcelUpload extends Controller
{
    public function fileupload()
    {
        return view('upload');
    }
    public function import_bkp(Request $request)
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
        // public function import(Request $request)
        // {
        //     $request->validate([
        //         'file' => 'required|mimes:xlsx,xls,csv'
        //     ]);

        //     $import = new UsersImport();
        //     Excel::import($import, $request->file('file'));

        //     if (!empty($import->failedRows)) {
        //         Mail::to('kumarjha271190@gmail.com')->send(new UserImportNotification('Some rows failed to import.'));
        //         return back()->with('error', 'Some rows failed to import.')->with('failedRows', $import->failedRows);
        //     }

        //     Mail::to('kumarjha271190@gmail.com')->send(new UserImportNotification('Users imported successfully!'));
        //     return back()->with('success', 'Users imported successfully!');
        // }

        public function import(Request $request)
        {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv'
            ]);
    
            $import = new UsersImport(); // Create an instance to track failed rows
            Excel::import($import, $request->file('file'));
    
            if (!empty($import->failedRows)) {
                Mail::to('kumarjha271190@gmail.com')->send(new UserImportNotification('Users imported successfully!'));
    
                return back()->with('error', 'Some rows failed to import.')
                             ->with('failedRows', $import->failedRows);
            }
    
            Mail::to('kumarjha271190@gmail.com')->send(new UserImportNotification('Users imported successfully!'));
    
            return back()->with('success', 'Users imported successfully!');
        }
}
