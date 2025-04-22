<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'name',
        'model',
        'status',
        'project_name',
        'purchase_date',
        'delivery_date',
        'notes'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'delivery_date' => 'date'
    ];
}
