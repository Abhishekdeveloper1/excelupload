<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue; 
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersImport implements ToModel,WithHeadingRow,WithChunkReading,ShouldQueue
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function chunkSize(): int
    {
        return 2; // Process 1000 rows at a time
    }
    public function model(array $row)
    {
        $validator = Validator::make($row, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            // Store failed rows with errors
            $this->failedRows[] = [
                'row' => $row,
                'errors' => $validator->errors()->all(),
            ];
            return null; // Skip inserting this row
        }
        return new User([
            
            'name'=>$row['name'],
            'email'=>$row['email'],
            'password'=>bcrypt($row['password']),
        ]);
    }
}
