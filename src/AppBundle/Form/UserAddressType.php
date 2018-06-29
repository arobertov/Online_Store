<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAddressType extends AbstractType {
	public function buildForm( FormBuilderInterface $builder, array $options ) {
	   $builder
		   ->add('city',TextType::class)
		   ->add('shipAddress',TextType::class)
		   ->add('phoneNumber',TextType::class)
	   ;
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver
			->setDefault('data_class','AppBundle\Entity\UserAddress');
	}

	public function getBlockPrefix() {
		return 'app_bundle_user_address_type';
	}
}
