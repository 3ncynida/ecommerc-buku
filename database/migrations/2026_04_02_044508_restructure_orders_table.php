<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1. Create order_items table
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items');
            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->timestamps();
        });

        // 2. Migrate existing data from orders to order_items
        $orders = DB::table('orders')->get();
        foreach ($orders as $order) {
            if ($order->item_id && $order->quantity) {
                $item = DB::table('items')->where('id', $order->item_id)->first();
                $price = $item ? $item->price : ($order->total_price / ($order->quantity ?: 1));

                DB::table('order_items')->insert([
                    'order_id' => $order->id,
                    'item_id' => $order->item_id,
                    'quantity' => $order->quantity,
                    'price' => $price,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                ]);
            }
        }

        // 3. Drop columns item_id and quantity from table orders
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropColumn(['item_id', 'quantity']);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('item_id')->nullable()->constrained('items');
            $table->integer('quantity')->default(1);
        });

        // Restore data
        $orderItems = DB::table('order_items')->get();
        foreach ($orderItems as $item) {
            DB::table('orders')->where('id', $item->order_id)->update([
                'item_id' => $item->item_id,
                'quantity' => $item->quantity,
            ]);
        }

        Schema::dropIfExists('order_items');
    }
};
