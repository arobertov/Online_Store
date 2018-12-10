<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 10.12.2018 г.
 * Time: 18:10
 */

namespace ShopBundle\Services;


use ShopBundle\Entity\PurchaseProduct;

interface PurchaseProductServiceInterface {

	public function addPurchaseToCartSession(PurchaseProduct $product);

}