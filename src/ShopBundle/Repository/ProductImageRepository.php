<?php

namespace ShopBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use ShopBundle\Entity\ProductImage;
use Doctrine\ORM\Mapping;
use Symfony\Component\Filesystem\Exception\IOException;

/**
 * ProductImageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductImageRepository extends \Doctrine\ORM\EntityRepository
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
		parent::__construct( $em, new Mapping\ClassMetadata(ProductImage::class) );
		$this->em = $em;
	}

	/**
	 * @param ProductImage $image
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function createImage(ProductImage $image){
		try{
			$this->em->persist($image);
			$this->em->flush();
			return $image->getPath().' upload  successful !';
		}  catch (\Exception $e){
			throw new \Exception($image->getPath().' unable upload ! Duplicate image name !');
		}

	}

	/**
	 * @return mixed
	 * @throws \Exception
	 */
	public function findAllImages(){
		try{
			$query = $this->em->createQuery(
				'SELECT im FROM ShopBundle\Entity\ProductImage im 
				 ORDER BY im.dateUpload DESC'
			);
			return $query;
		}catch (\Exception $e) {
			throw  new \Exception( 'Error: ' . $e->getMessage() );
		}
	}

	/**
	 * @param $category
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function findImagesByCategory($category){
		try {
			$query = $this->em->createQueryBuilder()
			                  ->select('im','cat')
			                  ->from('ShopBundle:ProductImage','im')
			                  ->where('cat.id=?1')
			                  ->orWhere('cat.root=?1')
			                  ->leftJoin('im.category','cat')
			                  ->orderBy('im.dateUpload','DESC')
			                  ->setParameter(1,$category)
			                  ->getQuery()
			;
			return $query;
		} catch (\Exception $e) {
			throw  new \Exception( 'Error: ' . $e->getMessage() );
		}
	}

	/**
	 * @param $ids
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function deleteImagesByIds($ids){
		try{
			$query = $this->em->createQueryBuilder()
			         ->delete('ShopBundle:ProductImage i')
			         ->where('i.id IN (:ids)')
			         ->setParameter('ids',$ids)
			         ->getQuery()
			;
			$query->getResult();
			return count($ids) . ' images delete successful !';
		} catch (\Exception $e){
			throw new \Exception('ERROR !Unable delete this images !');
		}

	}

	/**
	 * @param $ids
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function findImagesByIds($ids){
		try{
			$query = $this->em->createQueryBuilder()
			                  ->select('i')
			                  ->from('ShopBundle:ProductImage','i')
			                  ->where('i.id IN (:ids)')
			                  ->setParameter('ids',$ids)
			                  ->getQuery()
			;
			return $query->getResult();
		} catch (IOException $e){
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * @param $ids
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function findPathNameByIds($ids){
		try{
			$query = $this->em->createQueryBuilder()
			                  ->select('i.path')
			                  ->from('ShopBundle:ProductImage','i')
			                  ->where('i.id IN (:ids)')
			                  ->setParameter('ids',$ids)
			                  ->getQuery()
			;
			return $query->getResult();
		} catch (IOException $e){
			throw new \Exception($e->getMessage());
		}
	}
}
