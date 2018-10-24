<?php

namespace ShopBundle\Form;

use Doctrine\ORM\EntityRepository;
use ShopBundle\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType {
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		 $builder
			 ->add('path',FileType::class )
			 ->add('category',EntityType::class,array(
				 'class'=>'ShopBundle\Entity\Category',
				 'required' => false,
				 'placeholder'=>'Without Category',
				 'choice_label'=> function (Category $category) {
					 return $category->getParent() ?
						 "-- " . $category->getTitle() : strtoupper($category->getTitle());
				 },
				 'query_builder' => function(EntityRepository $er) {
					 return $er->createQueryBuilder('c')
					           ->orderBy('c.root', 'ASC')
					           ->addOrderBy('c.lft', 'ASC');
				 },
			 )) ;
		 ;
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults(['data_class'=>'ShopBundle\Entity\ProductImage']);
	}

	public function getBlockPrefix() {
		return 'shop_bundle_image_type';
	}
}
