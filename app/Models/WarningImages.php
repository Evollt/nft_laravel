<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarningImages extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'warning_id'
    ];
}
