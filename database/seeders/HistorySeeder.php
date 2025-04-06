<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    // baru di buat seed nya 
    public function run()
    {
        $data = [
            ['year' => 2020, 'value' => 3200],
            ['year' => 2021, 'value' => 6000],
            ['year' => 2022, 'value' => 1000],
            ['year' => 2023, 'value' => 4000],
        ];

        foreach ($data as $item) {
            \App\Models\History::updateOrInsert(
                ['year' => $item['year']], // Cek berdasarkan tahun
                ['value' => $item['value']] // Update nilai jika tahun sudah ada
            );
        }
    }
}
