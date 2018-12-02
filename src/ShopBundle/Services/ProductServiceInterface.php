<?php
/**
 * Created by PhpStorm.
 * User: AngelRobertov
 * Date: 22.8.2018 г.
 * Time: 20:30
 */

namespace ShopBundle\Services;


use Doctrine\Common\Collections\ArrayCollection;
use ShopBundle\Entity\Product;

interface ProductServiceInterface {

	public function createProduct(Product $product,array $images);

	public function editProduct(Product $product,array $images);

	public function removeProduct(Product $product);

	public function getAllProducts();

	public function getAllProductsByCategory($category);

	public function getProductsByPromotions();
}