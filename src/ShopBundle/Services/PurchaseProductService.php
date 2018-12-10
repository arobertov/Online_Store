<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 10.12.2018 г.
 * Time: 18:11
 */

namespace ShopBundle\Services;


use ShopBundle\Entity\PurchaseProduct;
use Symfony\Component\HttpFoundation\Session\Session;

class PurchaseProductService implements PurchaseProductServiceInterface {

	public function addPurchaseToCartSession(PurchaseProduct $product) {
		$session = new Session();
		$sessionCart = array();
		if($session->has('product_id')){
			$sessionCart = $session->get('product_id');
		}
		if(key_exists($product->getId(),$sessionCart)){
			$oldQty = $sessionCart[$product->getId()]->getProductQuantity();
			$product->setProductQuantity($product->getProductQuantity()+$oldQty);
		}
		$sessionCart[$product->getId()]=$product;
		$session->set('product_id',$sessionCart);

		dump($session->get('product_id'));
	}
	
}