<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    use HasFactory;

    protected $table = 'jobs';

    protected $fillable = [
        'user_id',
        'director_id',
        'file_id',
        'file_jobs',
        'status',       // 0: reject, 1 not assign, 2 assigned, 3 confirm, 4 done
        'time_upload',
        'time_confirm',
        'time_done',
        'type',         // 1: Photo editing	2: Day to dusk	3: Virtual Staging	4:Additional Retouching
        'editor_assign',
    ];

    /**
     * Function collection to table files
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function files()
    {
        return $this->hasOne('\App\Models\Files', 'id', 'file_id');
    }
}
