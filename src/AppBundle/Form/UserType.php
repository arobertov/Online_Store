<?php

namespace AppBundle\Form;

use AppBundle\Entity\UserAddress;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType {

	/**
	 * @var string
	 */
	static $fieldsSwitcher;

	/**
	 * @var EntityManagerInterface
	 */
	private $em;

	/**
	 * UserType constructor.
	 *
	 * @param EntityManagerInterface $em
	 */
	public function __construct( EntityManagerInterface $em ) {
		$this->em = $em;
	}


	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 *
	 * @throws \Doctrine\ORM\ORMException
	 */
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		if ( self::$fieldsSwitcher == 'change_password'){
			$builder
				->add('oldPassword',TextType::class)
				->add( 'plainPassword', RepeatedType::class, array(
				'type'           => PasswordType::class,
				'first_options'  => array( 'label' => 'Password' ),
				'second_options' => array( 'label' => 'Repeat Password' )
				) )
			;
		}
		if ( self::$fieldsSwitcher == 'edit' ) {
			$builder
				->add( 'username', TextType::class )
				->add( 'firstName', TextType::class, array(
					'required' => false
				) )
				->add( 'lastName', TextType::class, array(
					'required' => false
				) )
				->add( 'email', EmailType::class )
				->add( 'address', UserAddressType::class )
			;
		}
		if ( self::$fieldsSwitcher == 'registration' ) {
			$builder
				->add( 'username', TextType::class )
				->add( 'firstName', TextType::class, array(
					'required' => false
				) )
				->add( 'lastName', TextType::class, array(
					'required' => false
				) )
				->add( 'plainPassword', RepeatedType::class, array(
					'type'           => PasswordType::class,
					'first_options'  => array( 'label' => 'Password' ),
					'second_options' => array( 'label' => 'Repeat Password' )
				) )
				->add( 'email', EmailType::class )
				->add( 'address', UserAddressType::class )
			;
		}
		if ( $options['isSuperAdmin']==1 ) {
			$builder
				->add( 'roles', EntityType::class, array(
					'class'        => 'AppBundle:Role',
					'choice_label' => 'name',
					//set default value !!!!
					'data'         => $this->em->getReference( 'AppBundle:Role',
						$options['role']
					)
				) )
				->add( 'is_active', CheckboxType::class, array(
					'label'    => 'Activate User:',
					'required' => false
				) )
				->add( 'isNotLocked', CheckboxType::class, array(
					'label'    => 'Not Locked User',
					'required' => false
				) )
			;
		}
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver
			->setDefault( 'role', 'role' )
			->setDefault('isSuperAdmin','isSuperAdmin')
			->setDefault( 'data_class', 'AppBundle\Entity\User' )
			->setDefaults( array( 'validation_groups' => array( 'registration', 'change_password' ) ) );

	}

	public function getBlockPrefix() {
		return 'blog_bundle_user_type';
	}
}
