<?php

namespace ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartType extends AbstractType {
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add('productQuantity',IntegerType::class,[
				'label'=>false,
				'data'=>1,
				'required'=>false
			])
		;
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults(['data_class'=> 'ShopBundle\Entity\PurchaseProduct' ]);
	}

	public function getBlockPrefix() {
		return 'shop_bundle_cart_type';
	}
}
