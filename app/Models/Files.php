<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    use HasFactory;

    protected $table = 'files';

    protected $fillable = [
        'director_id',
        'image',
        'time_upload',
        'status',
        'thumbnail',
        'name',
    ];
}
