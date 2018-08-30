<?php
/**
 * Created by PhpStorm.
 * User: AngelRobertov
 * Date: 29.8.2018 г.
 * Time: 16:27
 */

namespace ShopBundle\Validator\Constraints;


use Doctrine\ORM\EntityManagerInterface;
use ShopBundle\Entity\Category;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ParentNameConstraintsValidator extends ConstraintValidator{

	/**
	 * @var EntityManagerInterface
	 */
	private $em;

	/**
	 * ParentNameConstraintsValidator constructor.
	 *
	 * @param EntityManagerInterface $em
	 */
	public function __construct( EntityManagerInterface $em ) {
		$this->em = $em;
	}


	/**
	 * Checks if the passed value is valid.
	 *
	 * @param mixed $value The value that should be validated
	 * @param Constraint $constraint The constraint for the validation
	 */
	public function validate( $value, Constraint $constraint ) {
		$categoryRepository = $this->em->getRepository(Category::class);
		$result = $categoryRepository->findOneBy(array('title'=>$value,'parent'=>null));
		$category = $this->context->getObject();
		if (!is_null($result) && $category->getParent() == null ) {
			$this->context->buildViolation($constraint->message)
				->addViolation();
		}
	}
}