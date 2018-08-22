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

	public function createProduct( Product $product ) {
		$this->productRepository->createNewProduct($product);
	}

	public function editProduct( Product $product ) {
		// TODO: Implement editProduct() method.
	}

	public function removeProduct( Product $product ) {
		// TODO: Implement removeProduct() method.
	}

	/**
	 * @return mixed|string
	 */
	public function getAllProduct() {
		try{
			return $this->productRepository->findAllProducts();
		}catch (\Exception $e){
			return $e->getMessage();
		}
	}

}