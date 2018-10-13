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
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService implements ImageServiceInterface {

	private $defaultUploadDir;

	private $readImageDir;

	private $imageRepository;

	public function __construct($uploadDirectory,$readImageDir,ProductImageRepository $imageRepository)
	{
		$this->readImageDir = $readImageDir;
		$this->defaultUploadDir = $uploadDirectory;
		$this->imageRepository  = $imageRepository;
	}

	/**
	 * @param UploadedFile $file
	 * @param ProductImage $image
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function uploadImage( ProductImage $image ) {
		/** @var UploadedFile $file */
		$file = $image->getPath();
		$file->move($this->getDefaultUploadDir(), $file->getClientOriginalName());
		$image->setPath($this->getDefaultUploadDir());
		try {
			return ['message'=>$this->imageRepository->createImage( $image ),'info'=>$file];
		} catch ( \Exception $e ) {
			throw new \Exception($e->getMessage());
		}

	}

	public function deleteImage( ProductImage $image ) {
		// TODO: Implement deleteImage() method.
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
	 * @param null $imagesDir
	 *
	 * @return Finder
	 */
	public function readImagesDir( $imagesDir = null ) {
		$files = new Finder();
		if($imagesDir == null){
			return $files->in($this->readImageDir);
		}
		return $files->in($this->readImageDir.'/\/'.$imagesDir);
	}
}