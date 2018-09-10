<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 26.8.2018 г.
 * Time: 1:35
 */

namespace ShopBundle\Services;


use ShopBundle\Entity\Category;

interface CategoryServiceInterface {

	public function listCategoriesBySidebar();

	public function listCategoriesByAdminPanel();

	public function getAllCategoriesOrderByParentChildren();

	public function createNewCategory(Category $category);

	public function editCategory(Category $category);

	public function deleteCategory(Category $category);
}