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
        Schema::create('asset_masters', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
            $table->string('name');
            $table->string('project_name')->nullable();
            $table->enum('model', ['Router', 'Firewall', 'Access Point', 'Accessories']);
            $table->string('status')->nullable();
            $table->date('asset_recieved')->nullable();
            $table->date('asset_shipped')->nullable();
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('id_insert')->nullable();
            $table->date('date_insert')->nullable();
            $table->unsignedBigInteger('id_update')->nullable();
            $table->date('date_update')->nullable();
            $table->unsignedBigInteger('id_delete')->nullable();
            $table->date('date_delete')->nullable();
            $table->integer('is_delete')->default(0);
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
        Schema::dropIfExists('asset_masters');
    }
};
