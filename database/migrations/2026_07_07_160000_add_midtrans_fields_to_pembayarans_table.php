<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->string('midtrans_order_id')->nullable()->unique()->after('paid_at');
            $table->text('snap_token')->nullable()->after('midtrans_order_id');
            $table->string('transaction_id')->nullable()->after('snap_token');
            $table->string('transaction_status')->nullable()->after('transaction_id');
            $table->string('payment_type')->nullable()->after('transaction_status');
            $table->string('fraud_status')->nullable()->after('payment_type');
            $table->longText('raw_response')->nullable()->after('fraud_status');
        });
    }

    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropUnique(['midtrans_order_id']);
            $table->dropColumn([
                'midtrans_order_id',
                'snap_token',
                'transaction_id',
                'transaction_status',
                'payment_type',
                'fraud_status',
                'raw_response',
            ]);
        });
    }
};