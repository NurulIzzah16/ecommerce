<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $users = User::where('role', 'user')->get(['id', 'username', 'email', 'created_at']);
        return $users;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Username',
            'Email',
            'Created At',
        ];
    }
}
