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

    /**
     * @return mixed
     */
    public function getUserNotAssign();

    /**
     * @return mixed
     */
    public function getUserAssigned();

    /**
     * @param $qcId
     * @return mixed
     */
    public function userBelongQC($qcId);

    /**
     * @param $id
     * @return mixed
     */
    public function removeUserAssign($id);

    /**
     * @param $userId
     * @param $qcId
     * @return mixed
     */
    public function assignedUser($userId, $qcId);

    /**
     * @param $qcId
     * @return mixed
     */
    public function listIdUserBelongQc($qcId);

    /**
     * @param $userId
     * @return mixed
     */
    public function getPriority($userId);

    /**
     * @param $userId
     * @return mixed
     */
    public function getIdUserAssignPriority($userId);

    /**
     * @return mixed
     */
    public function getAllIdUserAssignPriority();

    /**
     * @param $arrIdUserAssigned
     * @return mixed
     */
    public function getUserUnAssignPriority($arrIdUserAssigned);

    /**
     * @param $editorId
     * @param $userId
     * @param $priority
     * @return mixed
     */
    public function assignPriority($editorId, $userId, $priority);

    /**
     * @param $editorId
     * @param $userId
     * @return mixed
     */
    public function removeAssignPriority($editorId, $userId);
}
