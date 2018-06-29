<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 26.5.2018 г.
 * Time: 18:00
 */

namespace AppBundle\Services;


use AppBundle\Entity\User;

interface UserServiceInterface {

	public  function registerUser(User $user);

	public function editUser(User $user);

	public function removeUser(User $user);

	public function forgotPassword(array $formData);

	public function changePassword(User $user);

	public function checkRegisteredUserDate();

}