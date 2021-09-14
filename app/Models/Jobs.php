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

    /**
     * Function collection to table users
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function users()
    {
        return $this->hasOne('\App\Models\User', 'id', 'user_id');
    }

    /**
     * Function collection to table users
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function editor()
    {
        return $this->hasOne('\App\Models\User', 'id', 'editor_assign');
    }

    /**
     * Static function compare status text
     *
     *  0: reject, 1 no assign, 2 assigned, 3 confirm, 4 done
     * @param $status
     * @return string
     */
    public static function compareStatus($status)
    {
        switch ($status) {
            case 0:
                return 'Rejected';
            case 1:
                return 'No assign';
            case 2:
                return 'Assigned';
            case 3:
                return 'confirm';
            case 4:
                return 'Done';
            default:
                return '-';
        }
    }

    /**
     * Static function compare type text
     *
     *  1: Photo editing 2: Day to dusk	3: Virtual Staging	4: Additional Retouching
     * @param $type
     * @return string
     */
    public static function compareType($type)
    {
        switch ($type) {
            case 1:
                return 'Photo editing';
            case 2:
                return 'Day to dusk';
            case 3:
                return 'Virtual Staging';
            case 4:
                return 'Additional Retouching';
            default:
                return '-';
        }
    }
}
