<?php

namespace ShopBundle\Controller;

use ShopBundle\Entity\PurchaseProduct;
use ShopBundle\Entity\Product;
use ShopBundle\Services\ProductServiceInterface;
use ShopBundle\Services\PurchaseProductServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


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
}
