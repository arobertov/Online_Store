<?php
/**
 * Created by PhpStorm.
 * User: AngelRobertov
 * Date: 29.1.2019 г.
 * Time: 22:42
 */

namespace AppBundle\Services;


use AppBundle\Entity\User;
use ShopBundle\Entity\ClientOrder;

interface SendEmailServiceInterface {
	public function verifyRegistrationEmail(User $user);
	public function forgotPasswordEmail($randomPassword,User $user);
	public function sendOrderConfirmEmail(ClientOrder $order,User $user): ?string;
}