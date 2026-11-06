<?php

use App\Models\Order;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Order::query()->where('number', 'like', 'NM-%')->each(function (Order $order) {
            $order->forceFill(['number' => 'MOJ-'.substr($order->number, 3)])->saveQuietly();
        });
    }

    public function down(): void
    {
        Order::query()->where('number', 'like', 'MOJ-%')->each(function (Order $order) {
            $order->forceFill(['number' => 'NM-'.substr($order->number, 4)])->saveQuietly();
        });
    }
};
