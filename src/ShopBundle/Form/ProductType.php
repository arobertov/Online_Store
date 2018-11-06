<?php

namespace ShopBundle\Form;

use Doctrine\ORM\EntityRepository;
use ShopBundle\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('title',TextType::class)
            ->add('quantity',IntegerType::class)
            ->add('description',TextType::class)
            ->add('features',TextareaType::class)
            ->add('information',TextareaType::class)
            ->add('rating',IntegerType::class,array(
	            'required'   => false
            ))
	        ->add('category',EntityType::class,array(
		        'class'=>'ShopBundle\Entity\Category',
		        'choice_label'=> function (Category $category) {
			        return $category->getParent() ?
				        "-- " . $category->getTitle() : strtoupper($category->getTitle());
		        },
		        'query_builder' => function(EntityRepository $er) {
			        return $er->createQueryBuilder('c')
			                  ->orderBy('c.root', 'ASC')
			                  ->addOrderBy('c.lft', 'ASC');
		        },
	        ))
	        ->add('promotion', EntityType::class, array(
		        'class' => 'ShopBundle\Entity\Promotion',
		        'choice_label' => 'title',
		        'placeholder' => 'Without promotion !',
		        'required' => false
	        ))
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ShopBundle\Entity\Product',
	        'label'=>false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'shopbundle_product';
    }

}
