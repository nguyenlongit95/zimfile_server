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
        'qc_id',
        'qty',
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

    /**
     * Model method function convert type of directories
     *
     * 1: Photo editing 2: Day to dusk    3: Virtual Staging    4: Additional Retouching
     * @param $director
     * @return mixed
     */
    public static function convertType($director)
    {
        // Check case type and convert data
        switch ($director->type) {
            case 1:
                $director->type_txt = 'Photo editing';
                break;
            case 2:
                $director->type_txt = 'Day to dusk';
                break;
            case 3:
                $director->type_txt = 'Virtual Staging';
                break;
            case 4:
                $director->type_txt = 'Additional Retouching';
                break;
            default:
                $director->type_txt = '-';
                break;
        }
        // Response data director
        return $director;
    }

    /**
     * Model method convert the status of directories
     *
     * 0: reject, 1 chưa assign, 2 đã asign, 3 confirm, 4 done
     * @param $director
     * @return mixed
     */
    public static function convertStatus($director)
    {
        // Check case type and convert data
        switch ($director->status) {
            case 0:
                $director->status_txt = 'Reject';
                break;
            case 1:
                $director->status_txt = 'None assign';
                break;
            case 2:
                $director->status_txt = 'Assigned';
                break;
            case 3:
                $director->status_txt = 'Confirm';
                break;
            case 4:
                $director->status_txt = 'Done';
                break;
        }
        // Response data director
        return $director;
    }
}
