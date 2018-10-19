<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 19.9.2018 г.
 * Time: 22:38
 */

namespace ShopBundle\Services;


use ShopBundle\Entity\ProductImage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImageServiceInterface {
	 public function uploadImage(ProductImage $image);

	 public function deleteImage(ProductImage $image);

	 public function deleteImagesByIds($ids);

	 public function updateImage(ProductImage $image);

	 public function listImages();

	 public function listImagesByCategory($category);
}