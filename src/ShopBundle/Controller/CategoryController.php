<?php

namespace ShopBundle\Controller;


use ShopBundle\Services\CategoryServiceInterface;
use Symfony\Component\Routing\Annotation\Route;;
use ShopBundle\Entity\Category;
use ShopBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CategoryController
 * @package ShopBundle\Controller
 *
 * @Route("category/")
 */
class CategoryController extends Controller {

	/**
	 * @var CategoryServiceInterface
	 */
	private $categoryService;

	/**
	 * CategoryController constructor.
	 *
	 * @param CategoryServiceInterface $categoryService
	 */
	public function __construct( CategoryServiceInterface $categoryService ) {
		$this->categoryService = $categoryService;
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function listAllCategoriesAction()
	{
		$tree = $this->categoryService->listCategoriesBySidebar();

		return $this->render( '@Shop/category/sidebar.html.twig', array(
			'tree' => $tree,
		));
	}


	/**
	 * @param Request $request
	 *
	 * @Route("create",name="create_category")
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function createNewCategoryAction (Request $request) {
		$category = new Category();
		$form = $this->createForm(CategoryType::class,$category);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()){
			try {
				$this->addFlash( 'success', $this->categoryService->createNewCategory( $category ) );

				return $this->redirectToRoute( 'home_page' );
			} catch ( \Exception $e ) {
				$this->addFlash('error',$e->getMessage());
			}
		}

		return $this->render('@Shop/category/create_category',array(
			'form'=>$form->createView()
		));
	}
}
