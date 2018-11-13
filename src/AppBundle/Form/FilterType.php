<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType {
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		foreach ($builder->getData() as $value){
			if(is_array($value)){
				print_r();
			}
		}

		$filterData = $builder->getData();

		$builder->add('choicesFilter',ChoiceType::class,array(
			'choices'=>$filterData
		));
	}

	public function configureOptions( OptionsResolver $resolver ) {

	}

	public function getBlockPrefix() {
		return 'app_bundle_filter_type';
	}
}
