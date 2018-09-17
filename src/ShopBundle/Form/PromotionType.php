<?php

namespace ShopBundle\Form;

use Doctrine\ORM\EntityRepository;
use ShopBundle\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('title',TextType::class)
	        ->add('description',TextType::class,array(
	        	'required'=>false
	        ))
	        ->add('discount',PercentType::class)
	        ->add('startDate',DateType::class, array(
		        'widget' => 'single_text'
	        ))
	        ->add('endDate',DateType::class, array(
		        'widget' => 'single_text'
	        ))
	        ->add('category',EntityType::class,array(
		        'class'=>'ShopBundle\Entity\Category',
		        'required'=>false,
		        'placeholder'=>'ALL CATEGORIES',
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
	        ->add('isActive',CheckboxType::class,array(
	        	'required'=>false,
	        	'label'=>'Activate'
	        ))
	        ->add('Submit',SubmitType::class)
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ShopBundle\Entity\Promotion'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'shopbundle_promotion';
    }


}
