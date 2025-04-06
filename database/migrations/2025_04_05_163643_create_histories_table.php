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

    // baru di buat struktur DB  
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->integer('year'); // Tipe data integer untuk tahun
            $table->integer('value'); // Nilai histori database
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('histories');
    }
};
