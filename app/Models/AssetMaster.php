<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMaster extends Model
{
    // use HasFactory;

    protected $fillable = [
        'serial_number', 'name', 'project_name', 'model', 'status',
        'asset_recieved', 'asset_shipped', 'location', 'notes',
        'id_insert', 'date_insert', 'id_update', 'date_update',
        'id_delete', 'date_delete', 'is_delete'
    ];
}
