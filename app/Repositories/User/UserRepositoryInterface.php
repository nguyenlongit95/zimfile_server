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

    /**
     * @param $param
     * @return mixed
     */
    public function listCustomers($param);

    /**
     * @param $id
     * @return mixed 
     */
    public function deleteCustomer($id);

    /**
     * @param $param
     * @return mixed 
     */
    public function listEditors($param);
    
    /**
     * @param $param
     * @return mixed 
     */
    public function listQC($param);
}
