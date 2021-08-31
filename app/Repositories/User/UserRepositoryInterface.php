<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    /**
     * @param $password
     * @return mixed
     */
    public function verifyPassword($password);

    /**
     * @param $sort
     * @return mixed
     */
    public function listUsers($sort);

    /**
     * @param $user
     * @return mixed
     */
    public function getDirAndImage($user);
}
