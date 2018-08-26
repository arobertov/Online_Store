<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 26.8.2018 г.
 * Time: 1:37
 */

namespace ShopBundle\Services;


use ShopBundle\Entity\Category;
use ShopBundle\Repository\CategoryRepository;

class CategoryService implements CategoryServiceInterface {

	/**
	 * @var CategoryRepository
	 */
	private $categoryRepository;

	/**
	 * CategoryService constructor.
	 *
	 * @param CategoryRepository $categoryRepository
	 */
	public function __construct( CategoryRepository $categoryRepository ) {
		$this->categoryRepository = $categoryRepository;
	}


	/**
	 * @return \Doctrine\ORM\Query
	 * @throws \Exception
	 */
	public function getCategoryTreeJoinProduct() {
		try {
			return $this->categoryRepository->findAllCategoriesJoinProducts();
		} catch ( \Exception $e ) {
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * @param Category $category
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function createNewCategory( Category $category ) {
		try {
			return $this->categoryRepository->createNewCategory($category);
		} catch (\Exception $e){
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * @param Category $category
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function editCategory( Category $category ) {
		try{
			return $this->categoryRepository->updateCategory($category);
		} catch (\Exception $e){
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * @param Category $category
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function deleteCategory( Category $category ) {
		try {
			return $this->categoryRepository->deleteCategory($category);
		}  catch (\Exception $e){
			throw new \Exception($e->getMessage());
		}
	}
}