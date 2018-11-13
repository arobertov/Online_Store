<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Form\FilterType;
use AppBundle\Form\ForgotPasswordType;
use AppBundle\Form\RoleType;
use AppBundle\Form\UserType;
use AppBundle\Services\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package AppBundle\Controller
 * @Route("/user")
 */
class UserController extends Controller {

	/**
	 * @var UserServiceInterface
	 */
	protected $userService;

	/**
	 * @var EntityManagerInterface $em
	 */
	protected $em;

	/**
	 * UserController constructor.
	 *
	 * @param UserServiceInterface $userService
	 */
	public function __construct( UserServiceInterface $userService,EntityManagerInterface $em) {
		$this->userService = $userService;
		$this->em = $em;
	}


	/**
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 * @Route("/register",name="user_register")
	 */
	public function registerAction( Request $request ) {
		$user               = new User();
		UserType::$fieldsSwitcher = 'registration';
		$form               = $this->createForm( UserType::class, $user, array(
			'validation_groups'=>array('Default','registration')
		) );
		$form->handleRequest( $request );
		if ( $form->isSubmitted() && $form->isValid() ) {
			try {
				$message = $this->userService->registerUser( $user );
			}   catch (Exception $e) {
				$this->addFlash('error',$e->getMessage());
				return $this->render( "@App/security/register.html.twig", [
					'form' => $form->createView()
				] );
			}
			$this->addFlash( 'success', $message );

			return $this->redirectToRoute( "home_page" );
		}

		return $this->render( "@App/security/register.html.twig", [
			'form' => $form->createView()
		] );
	}


	/**
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 *
	 * @Route("/add_role",name="create_role")
	 * @Security("has_role('ROLE_SUPER_ADMIN')")
	 */
	public function addUserRoleAction( Request $request ) {
		$role     = new Role();
		$em  = $this->em;
		$allRoles = $em->getRepository( 'AppBundle:Role' )->findAll();
		$form     = $this->createForm( RoleType::class, $role );
		$form->handleRequest( $request );
		if ( $form->isSubmitted() && $form->isValid() ) {
			$em->persist( $role );
			$em->flush();
			$this->addFlash('success','New Role create successful !');
			$allRoles = $em->getRepository( 'AppBundle:Role' )->findAll();
			$this->redirectToRoute('create_role',array(
				'roles' => $allRoles,
				'form'  => $this->createForm(RoleType::class,$role)->createView()
			));
		}

		return $this->render( '@App/security/add_role.html.twig', array(
			'roles' => $allRoles,
			'form'  => $form->createView()
		) );
	}

	/**
	 * @param User $user
	 *
	 * @return Response
	 * @Route("/my_profile/{id}",name="my_profile")
	 */
	public function viewMyProfileAction( User $user ) {

		return $this->render( '@App/security/view_user_profile.html.twig', [
			'user' => $user
		] );
	}

	/**
	 * @Route("/all_users",name="user_manager")
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function userManagerAction() {
		$users = $this->userService->listAllUsers();
		$roles = $this->getDoctrine()->getRepository(Role::class)->findAllRoles();
		$filterData = array('Name'=>'us.username','Email'=>'us.email','Role'=>array('Role'=>'name'));
		$filterForm = $this->createForm(FilterType::class,$filterData);
		return $this->render( '@App/security/all_users.html.twig', [
			'users' => $users,
			'roles' => $roles,
			'filterForm'=>$filterForm->createView()
		] );
	}

	/**
	 * @Security("has_role('ROLE_USER')")
	 * @param Request $request
	 * @param User $user
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 * @Route("/edit/{id}",name="user_edit")
	 */
	public function userEditAction( Request $request, User $user ) {
		$isSuperAdmin = $this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN');
		if($user->getid() !== $this->getUser()->getId() && ! $isSuperAdmin){
			throw $this->createAccessDeniedException('Unable to access this page!');
		}
		UserType::$fieldsSwitcher = 'edit';
		$editForm = $this->createForm( UserType::class, $user, array(
			'isSuperAdmin'=> $isSuperAdmin,
			'role' => $user->getRoleId(),
			'validation_groups'=>array('Default')
		) );
		$editForm->handleRequest( $request );
		if ( $editForm->isSubmitted() && $editForm->isValid() ) {
			$this->userService->editUser($user);
			
			$this->addFlash( 'success', 'Your profile edit successful !' );
			return $this->redirectToRoute( 'my_profile', [ 'id' => $user->getId() ] );
		}

		return $this->render( '@App/security/edit_user.html.twig', [ 'user' => $user, 'edit_form' => $editForm->createView() ] );
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 * @param User $user
	 * @Route("/delete/{id}",name="user_delete")
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function userDeleteAction( User $user ) {
		if(!($this->userService->removeUser($user))){
			$this->addFlash('error','User in username '. $user->getUsername() . 'unable delete !') ;
		}
		$this->addFlash('success','User in username '. $user->getUsername() . ' delete successful !');
		return $this->redirectToRoute( 'user_manager' );
	}

	/**
	 * Set user for link from activation email
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @Route("/activate_user/{id}",name="activate_user")
	 * @Method("GET")
	 *
	 * @param User $user
	 */
	public function activationEmailSetUser( User $user ) {
		$this->userService->checkRegisteredUserDate();
		if($user->isNotExpired() === true){
			$user->setIsActive( 1 );
			$this->em->flush();
			$this->addFlash('success',"You Account  successful activated !Please Login !");
			return $this->redirectToRoute( 'login' );
		}
		$this->addFlash('error',
			"Your Account  is not activated. More than 48 hours have elapsed since your registration!
			Please register again !"
		);
		return $this->redirectToRoute('user_register');
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @param Request $request
	 * @Route("/forgot_password",name="forgot_password")
	 */
	public function forgotPasswordAction( Request $request ) {
		$form = $this->createForm( ForgotPasswordType::class );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			try {
				$message = $this->userService->forgotPassword( $form->getData() );
			} catch ( Exception $e ) {
				$this->addFlash( 'error', $e->getMessage() );

				return $this->render( "@App/security/forgot_password.html.twig", array(
					'form' => $form->createView()
				) );
			}
			$this->addFlash( 'success', $message );
			return $this->redirectToRoute( 'login' );
		}

		return $this->render( "@App/security/forgot_password.html.twig", array(
			'form' => $form->createView()
		) );
	}


	/**
	 * @param User $user
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 * @Route("/change_password/{id}",name="change_password")
	 */
	public function changePasswordAction( User $user, Request $request ) {
		UserType::$fieldsSwitcher = 'change_password';
		$form = $this->createForm( UserType::class,$user,array(
			'validation_groups'=>array('registration','change_password')
		) );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			try {
				$message = $this->userService->changePassword( $user );
			} catch ( Exception $e ) {
				$this->addFlash( 'error', $e->getMessage() );

				return $this->render( '@App/security/change_password.html.twig', [
					'form' => $form->createView()
				] );
			}
			$this->addFlash( 'success', $message );

			return $this->redirectToRoute( 'my_profile', array( 'id' => $user->getId() ) );
		}

		return $this->render( '@App/security/change_password.html.twig', [
			'form' => $form->createView()
		] );
	}
}
