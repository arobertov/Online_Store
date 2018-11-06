<?php

namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use ShopBundle\Entity\ProductImage;
use ShopBundle\Services\CategoryServiceInterface;
use ShopBundle\Services\ImageServiceInterface;
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
	 * @var CategoryServiceInterface
	 */
	private $categoryService;

	/**
	 * @var ImageServiceInterface
	 */
	private $imageService;

	/**
	 * ProductController constructor.
	 *
	 * @param ProductServiceInterface $productService
	 * @param CategoryServiceInterface $categoryService
	 * @param ImageServiceInterface $imageService
	 */
	public function __construct( ProductServiceInterface $productService, CategoryServiceInterface $categoryService,
		ImageServiceInterface $imageService) {
		$this->productService = $productService;
		$this->categoryService = $categoryService;
		$this->imageService =  $imageService;
	}


	/**
	 * @Route("list_products",name="list_all_products")
	 * @Method({"GET"})
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function listAllProductAction(Request $request){
		try{
			$categories = $this->categoryService->getAllCategoriesOrderByParentChildren();
			$products = $this->productService->getAllProduct();
		} catch (\Exception $e){
			$this->addFlash('error',$e->getMessage());
			return $this->redirectToRoute('admin_panel');
		}

		$category =  $request->get('category');
		if(isset($category)){
			try{
				$products = $this->productService->getAllProductsByCategory($category);
			}  catch (\Exception $e){
				$this->addFlash('error',$e->getMessage());
				return $this->redirectToRoute('admin_panel');
			}

		}

		return $this->render('@Shop/product/all_products_by_admin.html.twig',array(
			'products'=>$products,
			'categories'=>$categories
		));
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
		$categories = $this->categoryService->getAllCategoriesOrderByParentChildren();
		$form = $this->createForm(ProductType::class,$product);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()){
			try {
				$ids = explode(',',$request->request->get('image_ids'));
				$images =  $this->imageService->findImagesByIds($ids);
				$this->addFlash('success',$this->productService->createProduct( $product , $images ));
				return $this->redirectToRoute( 'list_all_products' );
			}catch (\Exception $e){
				$this->addFlash('danger',$e->getMessage());
				return $this->redirectToRoute( 'list_all_products' );
			}
		}

		return $this->render('@Shop/product/create_product.html.twig',
			['form'=>$form->createView(),'categories'=>$categories]
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
				return $this->redirectToRoute('list_all_products');
			} catch (\Exception $e){
				$this->addFlash('error',$e->getMessage());
				return $this->redirectToRoute('list_all_products');
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
				return $this->redirectToRoute('list_all_products');
			} catch (\Exception $e){
				$this->addFlash('danger',$e->getMessage());
			}
		return $this->redirectToRoute('list_all_products');
	}
}
