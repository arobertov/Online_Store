<?php

namespace ShopBundle\Controller;

use ShopBundle\Entity\ProductImage;
use ShopBundle\Form\ImageType;
use ShopBundle\Services\ImageServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends Controller {

	/**
	 * @var ImageServiceInterface $imageService
	 */
	private $imageService;

	/**
	 * ImageController constructor.
	 *
	 * @param ImageServiceInterface $imageService
	 */
	public function __construct( ImageServiceInterface $imageService ) {
		$this->imageService = $imageService;
	}

	 /**
   	 * @param Request $request
	 * @Route("/admin_panel/image_manager",name="image_manager")
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function indexAction( Request $request ) {
		$image = new ProductImage();
		$form = $this->createForm(ImageType::class,$image);
		$form->handleRequest($request);
		$path = $request->get('dirname');
		$finder = $this->imageService->readImagesDir($path);
		$repoArray = array('message'=>'','info'=>'');
		if($form->isSubmitted() && $form->isValid()){
			try{
				$repoArray = $this->imageService->uploadImage($image);
				$this->addFlash('success',$repoArray['message']);
			} catch (\Exception $e){
				$this->addFlash('danger',$e->getMessage());
			}

			//return $this->redirectToRoute('image_manager',['info'=>$repoArray['info']]);
		}
		return $this->render( 'image/upload.html.twig',array(
			'info'=>$repoArray['info'],
			'files'=>$finder,
			'form'=>$form->createView()
		) );
	}
}
