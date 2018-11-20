<?php
/**
 * Created by PhpStorm.
 * User: AngelRobertov
 * Date: 22.8.2018 г.
 * Time: 20:34
 */

namespace ShopBundle\Services;


use Doctrine\Common\Collections\ArrayCollection;
use Knp\Component\Pager\Paginator;
use ShopBundle\Entity\Product;
use ShopBundle\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class ProductService implements ProductServiceInterface {

	/**
	 * @var ProductRepository
	 */
	private $productRepository;

	/**
	 * @var Paginator
	 */
	private $paginator;

	/**
	 * @var RequestStack
	 */
	private $request;

	/**
	 * ProductService constructor.
	 *
	 * @param ProductRepository $productRepository
	 * @param Paginator $paginator
	 * @param RequestStack $request
	 */
	public function __construct( ProductRepository $productRepository,Paginator $paginator,RequestStack $request ) {
		$this->productRepository = $productRepository;
		$this->paginator = $paginator;
		$this->request = $request;
	}

	/**
	 * @param Product $product
	 * @param ArrayCollection $images
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function createProduct( Product $product,array $images ) {
		try{
			foreach ($images as $image){
				$product->addImage($image);
			}
			return $this->productRepository->createNewProduct($product);
		}catch (\Exception $e){
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * @param Product $product
	 *
	 * @param array $images
	 *
	 * @return mixed|string
	 * @throws \Exception
	 */
	public function editProduct( Product $product,array $images) {
		try {
			foreach ($images as $image){
				$product->addImage($image);
			}

			return $this->productRepository->updateProduct( $product );
		}catch (\Exception $e){
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * @param Product $product
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function removeProduct( Product $product ) {
		try{
			return $this->productRepository->deleteProduct($product);
		}catch (\Exception $e){
			throw new \Exception($e->getMessage());
		}
	}


	/**
	 * @return mixed|string
	 * @throws \Exception
	 */
	public function getAllProducts() {
		try{
			$query = $this->productRepository->findAllProducts();
			$request = $this->request->getCurrentRequest();
			$pagination = $this->paginator->paginate(
				$query,
				$request->query->getInt('page', 1),
				5
			);
			return $pagination;
		}catch (\Exception $e){
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * @param $category
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function getAllProductsByCategory($category){
		try{
			$query = $this->productRepository->findProductsByCategory($category);
			$request = $this->request->getCurrentRequest();
			$pagination = $this->paginator->paginate(
				$query,
				$request->query->getInt('page', 1),
				5
			);
			return $pagination;
		} catch (\Exception $e){
			throw new \Exception($e->getMessage());
		}
	}

}