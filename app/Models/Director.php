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
        'level',        // 0 has master folder, > 0 has sub folder
        'parent_id',    // id of master
        'path',         // Json string encode
        'type',         // 1: Photo editing	2: Day to dusk	3: Virtual Staging	4:Additional Retouching
        'status',       // 0: reject, 1 chưa assign, 2 đã asign, 3 confirm, 4 done
        'editor_id',    // id user of editor
        'note',
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
