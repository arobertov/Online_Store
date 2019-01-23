<?php

namespace ShopBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Doctrine\ORM\Mapping as ORM;
use ShopBundle\Entity\ClientOrder;
use ShopBundle\Entity\Product;
use ShopBundle\Entity\PurchaseProduct;
use ShopBundle\Services\ClientOrderServiceInterface;
use ShopBundle\Services\PurchaseProductServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseProductController extends Controller {

	/**
	 * @var PurchaseProductServiceInterface $purchaseProductService
	 */
	private $purchaseProductService;

	/**
	 * @var ClientOrder $clientOrderService
	 */
	private $clientOrderService;

	/**
	 * PurchaseProductController constructor.
	 *
	 * @param PurchaseProductServiceInterface $purchaseProduct
	 * @param ClientOrderServiceInterface $clientOrderService
	 */
	public function __construct( PurchaseProductServiceInterface $purchaseProduct,ClientOrderServiceInterface $clientOrderService ) {
		$this->purchaseProductService = $purchaseProduct;
		$this->clientOrderService = $clientOrderService;
	}

	/**
	 * @param Product $product
	 *
	 * @Route("product_cart/add_product/{id}",name="add_product_to_cart")
	 * @return Response
	 */
	public function addOneProductToCart(Product $product){
		$purchaseProduct = new PurchaseProduct($product);
		$purchaseProduct->setProductQuantity(1);
		try{
			$this->purchaseProductService->addPurchaseToCartSession($purchaseProduct);
		}catch (\Exception $e){
			$this->addFlash('error',$e->getMessage());
		}

		return $this->redirectToRoute('personal_cart');
	}

	/**
	 * @param Product $product
	 *
	 * @return  JsonResponse
	 * @Route("product_cart/remove_one_item/{id}",name="remove_item_to_cart")
	 */
	public function removeOneProductItemToCart(Product $product){
		$session = new Session();
		/** @var PurchaseProduct $productPurchase */
		$productPurchase = $this->purchaseProductService->removeItemCountProductToCart($product);
		$jsonResponse = array(
			'items_count'=>$session->get('product_count'),
			'subtotal'=>$productPurchase!==null?$productPurchase->getSubtotal():null,
			'product_count'=>$productPurchase!=null?$productPurchase->getProductQuantity():null,
			'product_price'=>$productPurchase->getRealPrice(),
			'product_discount'=>$productPurchase->getProductDiscount(),
			'total_discount'=>$session->get('total')['total-discount'],
			'total_price'=>$session->get('total')['total-price']
		);
		return new JsonResponse($jsonResponse);
	}

	/**
	 * @param $id
	 *
	 * @Route("product_cart/remove_product/{id}",name="remove_product_to_cart")
	 * @return Response
	 */
	public function removeProductToCart($id){
		$this->purchaseProductService->removeProductToCart($id);
		return $this->redirectToRoute('personal_cart');
	}

	/**
	 * @Route("/product_cart/clear",name="clear_cart")
	 */
	public function clearProductCart(){
		$session = new Session();
		$session->remove('product_cart');
		$session->remove('total');
		$session->remove('product_count');
		return $this->redirectToRoute('personal_cart');
	}

	/**
	 * @Route("/product_cart/finalize_shopping",name="finalize_shopping")
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function finalizingShopping(Request $request){
		$user = $this->getUser();
		if(!isset($user)){
			$user = new User();
		}
		dump($user);
		UserType::$fieldsSwitcher = 'create_order';
		$userForm = $this->createForm(UserType::class,$user,array(
			'validation_groups'=>array('Default',$this->getUser()==null?'unregistered':'')
		));
		$userForm->handleRequest($request);
		if($userForm->isSubmitted()&&$userForm->isValid()){
			try{
				$this->addFlash('success',$this->clientOrderService->createOrder($user));
				return $this->redirectToRoute('home_page');
			} catch (\Exception $e){
				$this->addFlash('danger',$e->getMessage());
			}
		}
		return $this->render( '@Shop/purchase/finalise_purchase.html.twig',array(
			'userForm'=>$userForm->createView()
		) );
	}
}
