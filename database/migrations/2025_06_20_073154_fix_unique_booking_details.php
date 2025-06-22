<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('booking_details', function (Blueprint $table) {
        // Tạo unique mới, có thêm date_book (bỏ qua việc drop cũ)
        $table->unique(['field_id', 'time_id', 'date_book'], 'field_time_date_unique');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
