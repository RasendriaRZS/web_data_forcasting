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
            // Cek dulu, hanya tambahkan kolom jika belum ada
    if (!Schema::hasColumn('assets', 'location')) {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('location')->nullable()->after('delivery_date');
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
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('location');
        });
    }
};
