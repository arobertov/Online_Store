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

	/**
	 * @param PurchaseProduct $product
	 *
	 * @return string
	 */
	public function addPurchaseToCartSession(PurchaseProduct $product):string {
		$session = new Session();
		$sessionCart = array();
		if($session->has('product_cart')){
			$sessionCart = $session->get('product_cart');
		}
		if(key_exists($product->getId(),$sessionCart)){
			$oldQty = $sessionCart[$product->getId()]->getProductQuantity();
			$product->setProductQuantity($product->getProductQuantity()+$oldQty);
		}
		$sessionCart[$product->getId()]=$product;
		$session->set('product_cart',$sessionCart);
		$this->calculateTotalSession($session,$sessionCart);
		$this->calculateProductCount($session,$sessionCart);
		return $product->getProductTitle().' added to your cart ';
	}

	private function calculateProductCount(Session $session,array $sessionCart){
		$productCount = 0;

		foreach ($sessionCart as $product){
			$productCount+= $product->getProductQuantity();
		}

		$session->set('product_count',$productCount);
	}

	/**
	 * @param Session $session
	 * @param array $sessionCart
	 */
	private function calculateTotalSession(Session $session,array $sessionCart):void {
		$total = 0;
		
		 foreach ($sessionCart as $product){
		 	$total += $product->getSubtotal();
		 }

		 $session->set('total',sprintf('%.2f',$total));
	}
	
}