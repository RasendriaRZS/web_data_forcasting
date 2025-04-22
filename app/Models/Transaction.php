<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'transaction_date',
        'project_name'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'serial_number', 'serial_number');
    }
}
