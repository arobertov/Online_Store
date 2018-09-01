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
		    $products = $this->productService->getAllProduct();
	    }catch (\Exception $e){
    		$this->addFlash('error',$e->getMessage());
	    }
        return $this->render('@Shop/Default/index.html.twig',array(
        	'products'=>$products
        ));
    }


	/**
	 * @Route("/admin_panel",name="admin_panel")
	 *
	 * @return Response
	 */
    public function adminPanelAction(){
    	return $this->render('admin_panel.html.twig');
    }

	/**
	 * @Route("/assetic",name="assetic_page")
	 * @return Response
	 */
    public function testAsseticAction(){
    	return $this->render('@Shop/Default/assetic.html.twig');
    }


}
