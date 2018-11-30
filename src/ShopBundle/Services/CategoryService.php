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
			$options = $this->setTreeOptions();
			return $this->categoryRepository->buildTree($query->getArrayResult(),$options);
		} catch ( \Exception $e ) {
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * @return array|string
	 * @throws \Exception
	 */
	public function listCategoriesByAdminPanel() {
		try {
			$query = $this->categoryRepository->findAllCategoriesJoinProducts();
			$options = $this->setTreeOptionsByAdminPanel();
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

	private function setTreeOptionsByAdminPanel(){
		return $options = array(
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
				$buttons = '<td>
							<div class="btn-group" role="group" aria-label="na">
								<a href="/category/edit/'.$node['slug'].'" type="button" class="btn btn-sm  btn-warning">Edit</a>
						    	<button type="button" data-id="'.$node['slug'].'" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteProductModal">Delete</button>
						    </div>
						</td>';
				if($node['lvl'] == 0) {
					return '<tr class="parent-tr"><td><a>' . $node['title'] . '</a></td>'.$buttons.'</tr>'.PHP_EOL;
				} else  return '<tr class="child-tr"><td><a href="/show_products/'.$node['slug'].'"><i class="icon-chevron-right"></i>'
				               .$node['title'].' ('.count($node['products']).')</a></td>'.$buttons.'</tr>'.PHP_EOL;
			} )
			;
	}

	private function setTreeOptions(){
		return $options = array(
				'decorate' => true,
				'rootOpen' => '',
				'rootClose' => '',
				'childOpen' => function($tree) {
					if(count($tree) && ($tree['lvl'] == 0)){
						return '<div class="card">'.PHP_EOL.
						            '<div class="card-header" id="heading-'.$tree['slug'].'">'.PHP_EOL.
						                '<h5 class="mb-0">'.PHP_EOL.
						                    '<button class="btn btn-link" type="button" data-toggle="collapse" 
												data-target="#'.$tree['slug'].'" aria-expanded="true" aria-controls="'.$tree['slug'].'">'
						                        . $tree['title'].
						                    '</button>'.PHP_EOL.
						                '</h5>'.PHP_EOL.
						            '</div>'.PHP_EOL.
						       '</div>'.PHP_EOL.
						       '<div id="'.$tree['slug'].'" class="collapse show" aria-labelledby="heading-'.$tree['slug'].'" data-parent="#sideManu">'.PHP_EOL.
						       '<div class="card-body">'
							;
					}

				},
				'childClose' => function($tree) {
					if(count($tree) && ($tree['lvl'] == 0)){
						return '</div>'.PHP_EOL.
							'</div>'.PHP_EOL;
					}
				},
				'nodeDecorator' => function($node) {
					if($node['lvl']!==0){
						return '<li><a href="/show_products/'.$node['slug'].'"><i class="icon-chevron-right"></i>'
						       .$node['title'].' ('.count($node['products']).')</a></li>'.PHP_EOL;
					}

				}
				);
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public function getAllCategoriesOrderByParentChildren() {
		try {
			return $this->categoryRepository->findAllCategoriesOrderByParentChild();
		}  catch (\Exception $e){
			throw new \Exception($e->getMessage());
		}
	}


	/**
	 * @param $category
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function listProductsByCategory( $category ) {
		$categories = $this->categoryRepository->findProductsByCategory($category);
		$products = array();
		foreach ($categories as $value){
			/** @var Category $value */
			$collections =  $value->getProducts()->getValues();
			foreach ($collections as $product){
				$products[] = $product;
			}
		}

		return $products;
	}

	/**
	 * @param $category
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function listImagesByCategory( $category ) {
		try {
			$categories = $this->categoryRepository->findImagesByCategory( $category );
			$images = array();
			foreach ($categories as $value){
				/** @var Category $value */
				$collections =  $value->getImages()->getValues();
				foreach ($collections as $image){
					$images[] = $image;
				}
			}
			return $images;
		} catch ( \Exception $e ) {
			throw new \Exception($e->getMessage());
		}
	}
}