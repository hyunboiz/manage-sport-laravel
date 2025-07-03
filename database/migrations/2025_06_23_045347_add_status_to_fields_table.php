<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToFieldsTable extends Migration
{
    public function up()
    {
        Schema::table('fields', function (Blueprint $table) {
            $table->string('status')->default('active')->after('price');
        });
    }

    public function down()
    {
        Schema::table('fields', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
