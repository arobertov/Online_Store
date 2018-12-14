<?php

namespace ShopBundle\Controller;

use ShopBundle\Entity\PurchaseProduct;
use ShopBundle\Entity\Product;
use ShopBundle\Services\ProductServiceInterface;
use ShopBundle\Services\PurchaseProductServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;


class HomepageController extends Controller
{

	/**
	 * @var ProductServiceInterface
	 */
	private $productService;

	/**
	 * @var PurchaseProductServiceInterface
	 */
	private $purchaseProductService;

	/**
	 * HomepageController constructor.
	 *
	 * @param ProductServiceInterface $productService
	 * @param PurchaseProductServiceInterface $purchaseProduct
	 */
	public function __construct( ProductServiceInterface $productService ,PurchaseProductServiceInterface $purchaseProduct ) {
		$this->productService         = $productService;
		$this->purchaseProductService = $purchaseProduct;
	}


	/**
     * @Route("/",name="home_page")
     */
    public function indexAction()
    {
    	try {
		    $latestProducts = $this->getDoctrine()->getRepository('ShopBundle:Product')
		                                          ->findBy([],['dateEdit'=>'DESC'],6);
		    $featuredProducts = $this->productService->getProductsByPromotions();
	    }catch (\Exception $e){
    		$this->addFlash('error',$e->getMessage());
    		$latestProducts=[];
	    }
        return $this->render('@Shop/Default/index.html.twig', [
        	'featuredProducts'=>$featuredProducts,
        	'latestProducts'=>$latestProducts
        ] );
    }

	/**
	 * @param Product $product
	 *
	 * @param Request $request
	 *
	 * @return Response
	 * @Route("product/show_details/{slug}",name="show_product")
	 */
	public function showProductDetailsAction(Product $product,Request $request){
		$purchaseProduct = new PurchaseProduct($product);
		$cartForm = $this->createForm('ShopBundle\Form\CartType',$purchaseProduct);
		$cartForm->handleRequest($request);
		if($cartForm->isSubmitted()){
			try{
				$message = $this->purchaseProductService->addPurchaseToCartSession($purchaseProduct);
				$this->addFlash('success',$message);
			}catch (\Exception $e){
				$this->addFlash('error',$e->getMessage());
			}
		}
		return $this->render('@Shop/product/product_details.html.twig',array(
			'product'=>$product,
			'cartForm'=>$cartForm->createView()
		));
	}


	/**
	 * @Route("/dashboard",name="admin_panel")
	 *
	 * @return Response
	 */
    public function adminPanelAction(){
    	return $this->render('admin_panel.html.twig');
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
		    $message = $this->purchaseProductService->addPurchaseToCartSession($purchaseProduct);
		    $this->addFlash('success',$message);
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
    	$jsonResponse = array('items_count'=>$session->get('product_count'),'subtotal'=>$productPurchase!==null?$productPurchase->getSubtotal():null,'product_count'=>$productPurchase!=null?$productPurchase->getProductQuantity():null,'total'=>$session->get('total'));
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

}
