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
         if (!Schema::hasTable('histories')) {
          Schema::create('histories', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('asset_id');
        $table->string('action'); // insert, update, delete
        $table->string('description')->nullable();
        $table->unsignedBigInteger('user_id')->nullable();
        $table->timestamps();
         });
        }
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
