<?php

namespace ShopBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ShopBundle\Entity\Product;
use ShopBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductController
 * @package ShopBundle\Controller
 *
 */
class ProductController extends Controller {

	/**
	 *
	 * @Route("/create",name="create_product")
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function createNewProductAction(Request $request){
		$product = new Product();
		$form = $this->createForm(ProductType::class,$product);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()){
			$em = $this->getDoctrine()->getManager();
			$em->persist($product);
			$em->flush();
			return $this->redirectToRoute('home_page');
		}

		return $this->render('@Shop/product/create_product.html.twig',
			['form'=>$form->createView()]
		);
	}

	/**
	 * @return Response
	 *
	 * @Route("/resp",name="resp_action")
	 */
	public function respAction() {
		return new Response('ddddddd');
	}
}
