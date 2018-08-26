<?php

namespace ShopBundle\Form;

use Doctrine\ORM\EntityRepository;
use ShopBundle\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yavin\Symfony\Form\Type\TreeType;

class CategoryType extends AbstractType {
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add('title',TextType::class)
			->add('parent',EntityType::class, [
				'class'=>'ShopBundle\Entity\Category',
				'placeholder'=>'First Level Category',
				'required'=>false,
				'choice_label'=> function (Category $category) {
					return $category->getParent() ?
						"-- " . $category->getTitle() : strtoupper($category->getTitle());
				},
				'query_builder' => function(EntityRepository $er) {
					return $er->createQueryBuilder('c')
					          ->orderBy('c.root', 'ASC')
					          ->addOrderBy('c.lft', 'ASC');
				},
			] )
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
