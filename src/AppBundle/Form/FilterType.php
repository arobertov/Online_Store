<?php

namespace AppBundle\Form;

use ShopBundle\Form\CategoryType;
use ShopBundle\Form\ProductType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\ResolvedFormTypeInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType {

	/**
	 * @var RequestStack
	 */
	private $request;

	/**
	 * @var array
	 */
	private $entityFields;

	/**
	 * FilterType constructor.
	 *
	 * @param RequestStack $request
	 */
	public function __construct(RequestStack $request ) {
		$this->request = $request;
	}

	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$choiceFields = $builder->getData();
		foreach ($builder->getData() as $key=>$value){
			if(is_array($value)){
				$choiceFields[$key] = $value['choice_value'];
				$this->entityFields[$value['choice_value']] = array('class'=>$value['class'],'choice_label'=>$value['choice_label']);
			}
		}

		$builder
			->setMethod('GET')
			->add('filterField',ChoiceType::class,array(
				'choices'=>$choiceFields,
				'label'=>false,
				'data'=>$this->request->getCurrentRequest()->query->get('filterField')
		));

		$builder->addEventListener(FormEvents::PRE_SET_DATA,function (FormEvent $event) {
			$request = $this->request->getCurrentRequest()->get('filterField');
			$selectedField = !$request ? null : $request;
			foreach ($event->getData() as $key=>$value){
				$checkSelectEntity = $selectedField==null ? false: key_exists($selectedField,$this->entityFields);
				if($checkSelectEntity or $selectedField==null?is_array($value):false){
					$entityClass = $checkSelectEntity ? $this->entityFields[$selectedField]['class']:$value['class'];
					$choiceLabel = $checkSelectEntity ? $this->entityFields[$selectedField]['choice_label']:$value['choice_label'];

					$event->getForm()->add('filterValue',EntityType::class,array(
						'class'=>$entityClass,
						'choice_label'=>$choiceLabel,
						'choice_value'=>$choiceLabel,
						'data'=>$this->request->getCurrentRequest()->query->get('filterValue'),
						'label'=>false
					));
				} else {
					$event->getForm()->add('filterValue',TextType::class,array(
						'label'=>false,
						'data'=>$this->request->getCurrentRequest()->query->get('filterValue')
					));
				}
				break;
			}
		});

	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults(array(
			'csrf_protection'=>false
		));
	}

	public function getBlockPrefix() {
		return null;
	}
}
