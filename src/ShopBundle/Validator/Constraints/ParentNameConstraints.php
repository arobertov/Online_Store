<?php
/**
 * Created by PhpStorm.
 * User: AngelRobertov
 * Date: 29.8.2018 г.
 * Time: 16:24
 */

namespace ShopBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ParentNameConstraints extends Constraint{

	public $message = "This name already contain at parent category !";

	public function validatedBy()
	{
		return \get_class($this).'Validator';
	}
}