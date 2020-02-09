<?php

namespace ShopBundle\Controller;


use ShopBundle\Services\CategoryServiceInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;;
use ShopBundle\Entity\Category;
use ShopBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CategoryController
 * @package ShopBundle\Controller
 *
 * @Route("dashboard/category/")
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
	 * embed sidebar controller
	 *
	 * @return Response
	 */
	public function listAllCategoriesAction($productCartCounter)
	{
		$tree = $this->categoryService->listCategoriesBySidebar();
		return $this->render( '@Shop/category/sidebar.html.twig', array(
			'tree' => $tree,
			'productCartCounter'=>$productCartCounter
		));
	}

	/**
	 *
	 * @Route("all_categories",name="list_categories")
	 * @return RedirectResponse|Response
	 */
	public function listCategoriesByAdminPanel(){
		try {
			$tree = $this->categoryService->listCategoriesByAdminPanel();
		} catch (\Exception $e){
			$this->addFlash('error',$e->getMessage());
			return $this->redirectToRoute('list_categories');
		}

		return $this->render('@Shop/category/list_all_categories',array(
			'tree'=>$tree
		));
	}


	/**
	 * @param Request $request
	 *
	 * @Route("create",name="create_category")
	 * @return RedirectResponse|Response
	 */
	public function createNewCategoryAction (Request $request) {
		$category = new Category();
		$form = $this->createForm(CategoryType::class,$category);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()){
			try {
				$this->addFlash( 'success', $this->categoryService->createNewCategory( $category ) );

				return $this->redirectToRoute( 'list_categories' );
			} catch ( \Exception $e ) {
				$this->addFlash('error',$e->getMessage());
			}
		}

		return $this->render('@Shop/category/create_category',array(
			'form'=>$form->createView()
		));
	}

	/**
	 * @param Category $category
	 * @param Request $request
	 *
	 * @Route("edit/{slug}",name="edit_category")
	 *
	 * @return RedirectResponse|Response
	 */
	public function editCategoryAction(Category $category,Request $request){
		$form = $this->createForm(CategoryType::class,$category);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()){
			try {
				$this->addFlash( 'success', $this->categoryService->editCategory( $category ) );
				return $this->redirectToRoute( 'list_categories' );
			} catch ( \Exception $e ) {
				$this->addFlash('error',$e->getMessage());
				return $this->redirectToRoute( 'list_categories' );
			}
		}

		return $this->render('@Shop/category/edit_category',array(
			'form'=>$form->createView()
		));
	}

	/**
	 * @param Category $category
	 *
	 * @Route("delete/{slug}",name="delete_category")
	 * @return RedirectResponse
	 */
	public function deleteCategoryAction(Category $category){
		try{
			$this->addFlash('success',$this->categoryService->deleteCategory($category));
			return $this->redirectToRoute('list_categories');
		} catch ( \Exception $e ) {
			$this->addFlash('danger',$e->getMessage());
			return $this->redirectToRoute( 'list_categories' );
		}

	}
}
