<?php

namespace ShopBundle\Controller;

use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use ShopBundle\Entity\ProductImage;
use ShopBundle\Form\CategoryImageType;
use ShopBundle\Form\ImageType;
use ShopBundle\Repository\ProductImageRepository;
use ShopBundle\Services\CategoryServiceInterface;
use ShopBundle\Services\ImageServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends Controller {

	/**
	 * @var ImageServiceInterface $imageService
	 */
	private $imageService;

	/**
	 * @var CategoryServiceInterface $categoryService
	 */
	private $categoryService;

	/**
	 * ImageController constructor.
	 *
	 * @param ImageServiceInterface $imageService
	 * @param CategoryServiceInterface $categoryService
	 */
	public function __construct( ImageServiceInterface $imageService,CategoryServiceInterface $categoryService ) {
		$this->imageService = $imageService;
		$this->categoryService = $categoryService;

	}

	/**
	 * @param Request $request
	 * @Route("/admin_panel/image_manager",name="image_manager")
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws \Exception
	 */
	public function indexAction( Request $request ) {
		$image = new ProductImage();
		$uploadForm = $this->createForm(ImageType::class,$image);
		$categoryForm = $this->createForm(CategoryImageType::class,$image);
		$uploadForm->handleRequest($request);
		$category =  $request->get('category');
		$images = $this->imageService->listImages();
		if(isset($category)){
			try{
				$images = $this->imageService->listImagesByCategory($category);
			}  catch (\Exception $e){
				$this->addFlash('error',$e->getMessage());
				return $this->redirectToRoute('image_manager');
			}
		}
		if($uploadForm->isSubmitted() && $uploadForm->isValid()){
			try{
				$this->addFlash('success',$this->imageService->uploadImage($image));
			} catch (\Exception $e){
				$this->addFlash('danger',$e->getMessage());
			}
			return $this->redirectToRoute('image_manager');
		}
		return $this->render( '@Shop/image/image_manager.html.twig',array(
			'categories'=>$this->categoryService->getAllCategoriesOrderByParentChildren(),
			'images'=>$images,
			'uploadForm'=>$uploadForm->createView(),
			'categoryForm'=>$categoryForm->createView()
		) );
	}

	public function addImagesToProductAction(){
		$images = $this->getDoctrine()->getRepository(ProductImage::class)->findAll();
		return $this->render('@Shop/image/add_images_content_modal',array(
			'images'=>$images,
			'categories'=>$this->categoryService->getAllCategoriesOrderByParentChildren()
		));
	}

	/**
	 * @Route("/admin_panel/edit_image/{id}",name="edit_image")
	 * @param ProductImage $image
	 * @param Request $request
	 *
	 * @return Response
	 * @Method({"POST"})
	 *
	 */
	public function editImageAction(ProductImage $image,Request $request){
		$form = $this->createForm(CategoryImageType::class,$image);
		$oldImageName = $image->getPath();

		$form->handleRequest($request);
		$em = $this->getDoctrine()->getManager();
		$em->flush();

		if($oldImageName !== $image->getPath()){
			$filesystem = new Filesystem();
			$uploadDir = $this->getParameter('upload_image_dir');
			$filesystem->rename($uploadDir.'/'.$oldImageName,$uploadDir.'/'.$image->getPath());
		}
		
		$response = array('path'=>$image->getPath(),'category'=>'___');
		if($image->getCategory() !== null){
			$response['category'] = $image->getCategory()->getTitle();
		}
		return new JsonResponse($response);
	}

	/**
	 * @Route("/admin_panel/delete_image",name="delete_image")
	 * @Method({"POST"})
	 * @param Request $request
	 * @return Response
	 */
	public function deleteImageAction(Request $request){
		try{
			$ids = explode(',',$request->request->get('ids'));
			$result = $this->imageService->deleteImagesByIds($ids);
			$this->addFlash('success',$result);
			return $this->redirectToRoute('image_manager');
		} catch (\Exception $e){
			$this->addFlash('danger',$e->getMessage());
			return $this->redirectToRoute('image_manager');
		}


	}
}
