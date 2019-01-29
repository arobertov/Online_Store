<?php
/**
 * Created by PhpStorm.
 * User: AngelRobertov
 * Date: 29.1.2019 г.
 * Time: 20:10
 */

namespace ShopBundle\Doctrine;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;

class RandomIdGenerator extends AbstractIdGenerator{

	/**
	 * Generates an identifier for an entity.
	 *
	 * @param EntityManager $em
	 * @param object|null $entity
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function generate( EntityManager $em, $entity ) {
		$entity_name = $em->getClassMetadata(get_class($entity))->getName();

		// Id must be 6 digits length, so range is 100000 - 999999
		$min_value = 10000000;
		$max_value = 99999999;

		$max_attempts = $min_value - $max_value;
		$attempt = 0;

		while (true) {
			$id = mt_rand($min_value, $max_value);
			try {
				$item = $em->find( $entity_name, $id );
			} catch ( OptimisticLockException $e ) {
			} catch ( TransactionRequiredException $e ) {
			} catch ( ORMException $e ) {
			}

			if (!$item) {
				return $id;
			}

			// Should we stop?
			$attempt++;
			if ($attempt > $max_attempts) {
				throw new \Exception('RandomIdGenerator worked hardly, but failed to generate unique ID :(');
			}
		}
	}
}