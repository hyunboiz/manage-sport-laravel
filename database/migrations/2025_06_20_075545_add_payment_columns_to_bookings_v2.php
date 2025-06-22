<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'payment_txnref')) {
                $table->string('payment_txnref')->nullable()->after('total');
            }
            if (!Schema::hasColumn('bookings', 'payment_transaction_no')) {
                $table->string('payment_transaction_no')->nullable()->after('payment_txnref');
            }
            if (!Schema::hasColumn('bookings', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_transaction_no');
            }
            if (!Schema::hasColumn('bookings', 'payment_note')) {
                $table->text('payment_note')->nullable()->after('paid_at');
            }
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'payment_txnref',
                'payment_transaction_no',
                'paid_at',
                'payment_note'
            ]);
        });
    }
};
