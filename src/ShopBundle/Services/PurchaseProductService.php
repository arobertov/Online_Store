<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 10.12.2018 г.
 * Time: 18:11
 */

namespace ShopBundle\Services;


use ShopBundle\Entity\Product;
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

	/**
	 * @param Product $product
	 *
	 * @return PurchaseProduct|null
	 */
	public function removeItemCountProductToCart($product): ?PurchaseProduct{
		$session = new Session();
		if($session->has('product_cart')){
			$products = $session->get('product_cart');
			/** @var PurchaseProduct $purchaseProduct */
			$purchaseProduct = $products[$product->getId()];
			$purchaseProduct->setProductQuantity( $purchaseProduct->getProductQuantity() - 1);
			if( $purchaseProduct->getProductQuantity() == 0){
				unset($products[$purchaseProduct->getId()]);
			}
			$session->set('product_cart',$products);
			$this->calculateProductCount($session,$products);
			$this->calculateTotalSession($session,$products);
			if(count($session->get('product_cart'))==0){
				$this->removeSessionsCart($session);
			}
			return $purchaseProduct;
		} return null;
	}

	public function removeProductToCart($id){
		$session = new Session();
		if($session->has('product_cart')){
			$products = $session->get('product_cart');
			unset($products[$id]);
			$session->set('product_cart',$products);
			$this->calculateProductCount($session,$products);
			$this->calculateTotalSession($session,$products);
		}
		if(count($session->get('product_cart'))==0){
			$this->removeSessionsCart($session);
		}
	}

	private function removeSessionsCart(Session $session){
		$session->remove('product_count');
		$session->remove('product_cart');
		$session->remove('total');
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
		$totalPrice = 0;
		$totalDiscount = 0;
		
		 foreach ($sessionCart as $product){
		 	$totalPrice += sprintf('%.2f',$product->getSubtotal());
		 	$totalDiscount += sprintf('%.2f',$product->getProductDiscount());
		 }
		 $total = array('total-price'=>$totalPrice,'total-discount'=>$totalDiscount);
		 $session->set('total',$total);
	}
	
}