<?php

namespace ShopBundle\Controller;

use ShopBundle\Services\ProductServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class HomepageController extends Controller
{

	/**
	 * @var ProductServiceInterface
	 */
	private $productService;

	/**
	 * HomepageController constructor.
	 *
	 * @param ProductServiceInterface $productService
	 */
	public function __construct( ProductServiceInterface $productService ) {
		$this->productService = $productService;
	}


	/**
     * @Route("/",name="home_page")
     */
    public function indexAction()
    {
    	try {
		    $products = $this->productService->getAllProducts();
	    }catch (\Exception $e){
    		$this->addFlash('error',$e->getMessage());
    		$products = [];
	    }
        return $this->render('@Shop/Default/index.html.twig', [
        	'products'=>$products
        ] );
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
