<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 18.12.2018 г.
 * Time: 13:06
 */

namespace ShopBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use ShopBundle\Entity\PurchaseProduct;


class PurchaseProductRepository extends EntityRepository {

	/**
	 * @var EntityManagerInterface $em
	 */
	private $em;

	/**
	 * PurchaseProductRepository constructor.
	 *
	 * @param EntityManagerInterface $em
	 */
	public function __construct( EntityManagerInterface $em ) {
		parent::__construct( $em, new Mapping\ClassMetadata(PurchaseProduct::class) );
		$this->em = $em;
	}


}