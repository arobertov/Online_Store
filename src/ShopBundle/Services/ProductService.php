<?php
/**
 * Created by PhpStorm.
 * User: AngelRobertov
 * Date: 22.8.2018 г.
 * Time: 20:34
 */

namespace ShopBundle\Services;


use ShopBundle\Entity\Product;
use ShopBundle\Repository\ProductRepository;

class ProductService implements ProductServiceInterface {

	/**
	 * @var ProductRepository
	 */
	private $productRepository;

	/**
	 * ProductService constructor.
	 *
	 * @param ProductRepository $productRepository
	 */
	public function __construct( ProductRepository $productRepository ) {
		$this->productRepository = $productRepository;
	}

	/**
	 * @param Product $product
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function createProduct( Product $product ) {
		try{
			return $this->productRepository->createNewProduct($product);
		}catch (\Exception $e){
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * @param Product $product
	 *
	 * @return mixed|string
	 * @throws \Exception
	 */
	public function editProduct( Product $product ) {
		try {
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
	public function getAllProduct() {
		try{
			return $this->productRepository->findAllProducts();
		}catch (\Exception $e){
			throw new \Exception($e->getMessage());
		}
	}

}