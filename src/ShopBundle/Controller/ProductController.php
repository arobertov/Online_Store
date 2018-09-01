<?php

namespace ShopBundle\Controller;

use ShopBundle\Services\ProductServiceInterface;
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
 * @Route("product/")
 */
class ProductController extends Controller {

	/**
	 * @var ProductServiceInterface
	 */
	private $productService;

	/**
	 * ProductController constructor.
	 *
	 * @param ProductServiceInterface $productService
	 */
	public function __construct( ProductServiceInterface $productService ) {
		$this->productService = $productService;
	}

	/**
	 *
	 * @Route("create",name="create_product")
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function createNewProductAction(Request $request){
		$product = new Product();
		$form = $this->createForm(ProductType::class,$product);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()){
			try {
				$this->addFlash('success',$this->productService->createProduct( $product ));
				return $this->redirectToRoute( 'home_page' );
			}catch (\Exception $e){
				$this->addFlash('error',$e->getMessage());
			}
		}

		return $this->render('@Shop/product/create_product.html.twig',
			['form'=>$form->createView()]
		);
	}


	/**
	 * @param Product $product
	 * @param Request $request
	 *
	 * @Route("edit/{slug}",name="edit_product")
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function editProductAction(Product $product,Request $request){
		$form = $this->createForm(ProductType::class,$product);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()){
			try{
				$this->addFlash('success',$this->productService->editProduct($product));
				return $this->redirectToRoute('home_page');
			} catch (\Exception $e){
				$this->addFlash('error',$e->getMessage());
				return $this->redirectToRoute('home_page');
			}
		}

		return $this->render( '@Shop/product/edit_product.html.twig',array(
			'form'=>$form->createView()
		));
	}


	/**
	 * @param Product $product
	 * @param Request $request
	 *
	 * @Route("delete/{slug}",name="delete_product")
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteProductAction(Product $product,Request $request){
			try{
				$this->addFlash('success',$this->productService->removeProduct($product));
				return $this->redirectToRoute('home_page');
			} catch (\Exception $e){
				$this->addFlash('error',$e->getMessage());
			}
		return $this->redirectToRoute('home_page');
	}
}
