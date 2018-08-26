<?php

namespace ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yavin\Symfony\Form\Type\TreeType;

class CategoryType extends AbstractType {
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add('title',TextType::class)
			->add('parent',TreeType::class,array(
				'class'=>'ShopBundle\Entity\Category',
				'placeholder'=>'First Level Category',
				'required'=>false,
				'levelPrefix' => '-',
				'orderFields' => ['lft' => 'asc'],
				'prefixAttributeName' => 'data-level-prefix',
				'treeLevelField' => 'lvl',
			))
		;
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults(array(
			'data_class' => 'ShopBundle\Entity\Category'
		));
	}

	public function getBlockPrefix() {
		return 'shop_bundle_category_type';
	}
}
