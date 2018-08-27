<?php

namespace ShopBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use MongoDB\Driver\Exception\ExecutionTimeoutException;
use ShopBundle\Entity\Category;
use Doctrine\ORM\Mapping;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends NestedTreeRepository
{
	/**
	 * @var EntityManagerInterface $em
	 */
	private $em;

	/**
	 * CategoryRepository constructor.
	 *
	 * @param EntityManagerInterface $em
	 */
	public function __construct( EntityManagerInterface $em ) {
		parent::__construct( $em, new Mapping\ClassMetadata(Category::class) );
		$this->em = $em;
	}

	/**
	 * @return \Doctrine\ORM\Query
	 * @throws \Exception
	 */
	public function findAllCategoriesJoinProducts(){
		try {
			$query = $this->em
				->createQueryBuilder()
				->select(array('node','products','children'))
				->from('ShopBundle:Category', 'node')
				->leftJoin('node.products','products')
				->leftJoin('node.children','children')
				->orderBy('node.root, node.lft', 'ASC')
				->getQuery()
			;
			return $query;
		}  catch (\Exception $e){
			throw  new \Exception('Error: '.$e->getMessage());
		}
	}

	/**
	 * @return mixed
	 * @throws \Exception
	 */
	public function findAllCategoriesTree(){
		try{
			$query = $this->em->createQuery('SELECT c ,cc ,pc,cp   
											  FROM ShopBundle\Entity\Category c
											  JOIN c.children cc
											  LEFT JOIN cc.products pc
											  LEFT JOIN cc.parent cp
											 ');
			return $query->getResult();
		} catch (\Exception $e){
			throw new \Exception('Error: '.$e->getMessage());
		}

	}

	/**
	 * @param Category $category
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function createNewCategory(Category $category){
		try{
			$em = $this->em;
			$em->persist($category);
			$em->flush();
			return 'Your category create successful !';
		}  catch (\Exception $e){
			throw  new \Exception('Error : ' . $e->getMessage());
		}

	}

	/**
	 * @param Category $category
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function updateCategory(Category $category){
		try{
			$em = $this->em;
			$em->flush();

			return $category->getTitle() . 'changed successful !';
		} catch (\Exception $e){
			throw new \Exception('Error: ' . $e->getMessage());
		}
	}

	/**
	 * @param Category $category
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function deleteCategory(Category $category){
		try{
			$this->em->remove($category);
			$this->em->flush();
			return $category->getTitle(). ' delete successful !';
		} catch (NoResultException $e) {
			throw new \Exception($e->getMessage());
		}
	}
}
