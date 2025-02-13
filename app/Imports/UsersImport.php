<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue
{
    public $failedRows = []; // ✅ Declare failedRows property

    /**
     * Define the chunk size for processing.
     */
    public function chunkSize(): int
    {
        return 1000; // ✅ Efficient batch processing
    }

    /**
     * Process each row.
     */
    public function model(array $row)
    {
        $validator = Validator::make($row, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            // ✅ Store failed rows for later retrieval
            $this->failedRows[] = [
                'row' => $row,
                'errors' => $validator->errors()->all(),
            ];
            return null; // Skip inserting this row
        }

        return new User([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => bcrypt($row['password']),
        ]);
    }
}
