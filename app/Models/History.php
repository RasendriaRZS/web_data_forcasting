<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'histories'; // Nama tabel di database
    protected $fillable = ['year', 'value']; // Kolom yang bisa diisi
}
