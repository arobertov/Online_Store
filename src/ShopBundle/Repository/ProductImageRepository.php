<?php

namespace ShopBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use ShopBundle\Entity\ProductImage;
use Doctrine\ORM\Mapping;

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
}
