<?php
/**
 * Created by PhpStorm.
 * User: AngelRobertov
 * Date: 22.8.2018 г.
 * Time: 20:30
 */

namespace ShopBundle\Services;


use ShopBundle\Entity\Product;

interface ProductServiceInterface {

	public function createProduct(Product $product);

	public function editProduct(Product $product);

	public function removeProduct(Product $product);

	public function getAllProduct();
}