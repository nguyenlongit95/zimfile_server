<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    use HasFactory;

    protected $table = 'directors';

    protected $fillable = [
        'user_id',
        'nas_dir',
        'vps_dir',
        'level',
        'parent_id',
        'path',
    ];

    /**
     * Function collect to files table has many
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany('\App\Models\Files', 'director_id', 'id');
    }

    /**
     * Function collect to table users has one
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function users()
    {
        return $this->hasOne('\App\Models\User', 'user_id', 'id');
    }
}
