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
	 * @throws \Exception
	 */
	public function listCategoriesBySidebar() {
		try {
			$query = $this->categoryRepository->findAllCategoriesJoinProducts();
			$options = array(
				'decorate' => true,
				'rootOpen' => function($tree) {
					if(count($tree) && ($tree[0]['lvl'] == 1)){
						return '<ul>'.PHP_EOL;
					}

				},
				'rootClose' => function($tree) {
					if(count($tree) && ($tree[0]['lvl'] == 1)){
						return '</ul>'.PHP_EOL;
					}
				},
				'childOpen' => '',
				'childClose' => '',
				'nodeDecorator' => function($node) {
					if($node['lvl'] == 0) {
						return '<li class="subMenu open"><a>' . $node['title'] . '</a>'.PHP_EOL;
					} else return '<li><a href="/show_products/'.$node['slug'].'"><i class="icon-chevron-right"></i>'
					              .$node['title'].' ('.count($node['products']).')</a></li>'.PHP_EOL;
				} )
			;
			return $this->categoryRepository->buildTree($query->getArrayResult(),$options);
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
			if($category->getParent()!== null){
				$category->setCode($category->getParent()->getTitle());
			}
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