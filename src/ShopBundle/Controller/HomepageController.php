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
	 * @Route("/dashboard",name="admin_panel")
	 *
	 * @return Response
	 */
    public function adminPanelAction(){
    	return $this->render('admin_panel.html.twig');
    }

}
