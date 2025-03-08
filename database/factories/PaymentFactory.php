<?php

namespace Database\Factories;
use App\Models\Payment;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'order_id' => Order::factory(),
            'user_id' => User::factory(),
            'payment_method' => $this->faker->randomElement(['transfer_bank', 'ewallet', 'credit_card']),
            'transaction_id' => Str::uuid(),
            'snap_token' => Str::random(20),
            'status' => $this->faker->randomElement(['pending', 'success', 'failed']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
