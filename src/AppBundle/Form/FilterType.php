<?php

namespace AppBundle\Form;

use AppBundle\AppBundle;
use AppBundle\Entity\Role;
use function PHPSTORM_META\type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Workflow\Event\Event;

class FilterType extends AbstractType {

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
		));

		$builder->addEventListener(FormEvents::PRE_SET_DATA,function (FormEvent $event) {
			$request = $this->request->getCurrentRequest()->request->getIterator();
			$selectedField = !$request->key()=='filterField' ? null : $request['filterField'];

			foreach ($event->getData() as $key=>$value){
				$checkSelectEntity = $selectedField==null ? false: key_exists($selectedField,$this->entityFields);
				if($checkSelectEntity || $selectedField==null?is_array($value):false){
					$entityClass = $checkSelectEntity ? $this->entityFields[$selectedField]['class']:$value['class'];
					$choiceLabel = $checkSelectEntity ? $this->entityFields[$selectedField]['choice_label']:$value['choice_label'];
					$event->getForm()->add('filterValue',EntityType::class,array(
						'class'=>$entityClass,
						'choice_label'=>$choiceLabel,
						'choice_value'=>$choiceLabel
					));
				} else {
					$event->getForm()->add('filterValue',TextType::class);
				}
				break;
			}
		});

		$builder->add('submit',SubmitType::class);
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
