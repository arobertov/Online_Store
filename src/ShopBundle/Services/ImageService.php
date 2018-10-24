<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 19.9.2018 г.
 * Time: 22:41
 */

namespace ShopBundle\Services;


use ShopBundle\Entity\ProductImage;
use ShopBundle\Repository\ProductImageRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService implements ImageServiceInterface {

	private $defaultUploadDir;

	private $imageRepository;

	public function __construct($uploadDirectory,ProductImageRepository $imageRepository)
	{
		$this->defaultUploadDir = $uploadDirectory;
		$this->imageRepository  = $imageRepository;
	}

	/**
	 * @return mixed
	 */
	public function getDefaultUploadDir() {
		return $this->defaultUploadDir;
	}

	/**
	 * @param string $defaultUploadDir
	 *
	 * @return $this
	 */
	public function setDefaultUploadDir(string $defaultUploadDir){
		$this->defaultUploadDir = $defaultUploadDir;

		return $this;
	}


	/**
	 * @param ProductImage $image
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function uploadImage( ProductImage $image ) {
		/** @var UploadedFile $file */
		$file = $image->getPath();
		$file->move($this->getDefaultUploadDir(), $file->getClientOriginalName());
		$image->setPath($file->getClientOriginalName());
		$image->setImageSize($file->getClientSize());
		$image->setDateUpload(new \DateTime('now'));
		try {
			return $this->imageRepository->createImage( $image );
		} catch ( \Exception $e ) {
			throw new \Exception($e->getMessage());
		}

	}

	public function deleteImage( ProductImage $image ) {
		
	}



	/**
	 * @throws \Exception
	 */
	public function listImages() {
		try {
			return $this->imageRepository->findAllImages();
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * @param $category
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function listImagesByCategory($category){
		try{
			return $this->imageRepository->findImagesByCategory($category);
		} catch ( \Exception $e ) {
			throw new \Exception($e->getMessage());
		}
	}

	public function updateImage( ProductImage $image ) {

	}

	/**
	 * @param $ids
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function deleteImagesByIds( $ids ) {
		try {
			$result = $this->imageRepository->findPathNameByIds($ids);
			$filesystem = new Filesystem();
			for($i=0;$i<count($result);$i++){
				$filesystem->remove($this->defaultUploadDir."\\".$result[$i]['path']);
			}
			
			return $this->imageRepository->deleteImagesByIds( $ids );
		} catch ( \Exception $e ) {
			throw new \Exception($e->getMessage());
		}
	}
}