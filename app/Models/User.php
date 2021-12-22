<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'phone',
        'verified_token',
        'status',   // status: tình trạng hoạt động của tài khoản: 0 block, 1 active
        'total_file',
        'base_path',
        'role',     // 0 adminstator, 1 user, 2 editor, 3 qc, 4 sub admin
        'group_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Function collect to files table has to many
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function directors()
    {
        return $this->hasMany('\App\Models\directors', 'user_id', 'id');
    }

    /**
     * Function collect to access token
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function authAcesToken()
    {
        return $this->hasMany('\App\Models\OauthAccessToken');
    }
}
