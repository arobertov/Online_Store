<?php

namespace ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType {
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		 $builder
			 ->add('path',FileType::class)
		     ->add('submit',SubmitType::class)
		 ;
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults(['data_class'=>'ShopBundle\Entity\ProductImage']);
	}

	public function getBlockPrefix() {
		return 'shop_bundle_image_type';
	}
}
