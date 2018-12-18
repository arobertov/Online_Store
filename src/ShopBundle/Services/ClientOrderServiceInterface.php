<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 16.12.2018 г.
 * Time: 17:08
 */

namespace ShopBundle\Services;


interface ClientOrderServiceInterface {

	public function createOrder();

	public function deleteOrder();

	public function ordersList();

	public function orderDetail();

}