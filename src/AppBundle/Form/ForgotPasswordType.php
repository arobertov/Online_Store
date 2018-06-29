<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForgotPasswordType extends AbstractType {
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add('email',EmailType::class)
			;
	}

	public function configureOptions( OptionsResolver $resolver ) {

	}

	public function getBlockPrefix() {
		return 'app_bundle_forgot_password_type';
	}
}
