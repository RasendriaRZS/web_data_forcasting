<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // Kolom serial_number sebagai foreign key ke assets.serial_number
            $table->string('serial_number');
            $table->foreign('serial_number')->references('serial_number')->on('assets')->onDelete('cascade');
            
            // Kolom transaction_date
            $table->date('transaction_date');
            
            // Kolom project_name (boleh null, mengikuti assets)
            $table->string('project_name')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
