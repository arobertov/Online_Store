<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 16.12.2018 г.
 * Time: 17:08
 */

namespace ShopBundle\Services;


use AppBundle\Entity\User;

interface ClientOrderServiceInterface {

	public function createOrder(User $user): ?string;

	public function deleteOrder();

	public function listAllOrders();

	public function orderDetail();

}