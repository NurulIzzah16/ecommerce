<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use Illuminate\Support\Str;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        Payment::insert([
            [
                'order_id' => 2,
                'user_id' => 1,
                'payment_method' => 'transfer_bank',
                'transaction_id' => Str::uuid(),
                'snap_token' => Str::random(20),
                'status' => 'success',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'order_id' => 3,
                'user_id' => 2,
                'payment_method' => 'ewallet',
                'transaction_id' => Str::uuid(),
                'snap_token' => Str::random(20),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        Payment::factory(2)->create();
    }
}
