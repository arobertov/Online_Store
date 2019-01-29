<?php
/**
 * Created by PhpStorm.
 * User: AngelRobertov
 * Date: 23.11.2017 г.
 * Time: 15:58
 */

namespace AppBundle\Services;


use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Paginator;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService implements UserServiceInterface {
	/**
	 * @var SendEmailService
	 */
	private $sendEmailService;

	/**
	 * @var EntityManagerInterface
	 */
	private $em;

	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $passwordEncoder;

	/**
	 * @var UserRepository $userRepository
	 */
	private $userRepository;

	/**
	 * @var Paginator
	 */
	private $paginator;

	/**
	 * @var RequestStack
	 */
	private $request;

	/**
	 * UserService constructor.
	 *
	 * @param SendEmailService $sendEmailService
	 * @param EntityManagerInterface $em
	 * @param UserPasswordEncoderInterface $encoder
	 * @param UserRepository $user_repository
	 * @param Paginator $paginator
	 * @param RequestStack $request
	 */
	public function __construct( SendEmailService $sendEmailService, EntityManagerInterface $em,
		UserPasswordEncoderInterface $encoder,UserRepository $user_repository,Paginator $paginator,RequestStack $request ) {
		$this->sendEmailService = $sendEmailService;
		$this->em               = $em;
		$this->passwordEncoder  = $encoder;
		$this->userRepository   = $user_repository;
		$this->paginator = $paginator;
		$this->request = $request;
	}


	/**
	 * @return string
	 * @throws \Exception
	 */
	public function checkRegisteredUserDate() {
		$em = $this->em;
		$users = $em->getRepository(User::class)->findAll();
		foreach ($users as $user){
			$dateRegistered = $user->getDateRegistered();
			$interval = $this->toSeconds($dateRegistered->diff(new \DateTime('now')));
			if($interval>=172800 && $user->getIsActive() == false){
				$user->setIsNotExpired(false);
				$em->flush();
			}
		}
		return "Ok";
	}

	private function toSeconds($interval)
	{
		return ($interval->y * 365 * 24 * 60 * 60) +
		       ($interval->m * 30 * 24 * 60 * 60) +
		       ($interval->d * 24 * 60 * 60) +
		       ($interval->h * 60 * 60) +
		       ($interval->i * 60) +
		       $interval->s;
	}


	/**
	 * @param User $user
	 *
	 * @return string
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 * @throws \Exception
	 */
	public  function registerUser(User $user){
        $password = $this->passwordEncoder
            ->encodePassword($user, $user->getPlainPassword());
        $em = $this->em;
        $user->setPassword($password);
		$user->setDateRegistered(new \DateTime('now'));
		$user->setDateEdit(new \DateTime('now'));
		$roleUser = $this->userRepository->findRoleUser(['name'=>'ROLE_USER']);
		$roleSuperAdmin = $this->userRepository->findRoleUser(['name'=>'ROLE_SUPER_ADMIN']);
		$roleAdminObject = new Role();
		$roleUserObject = new Role();
        //--  initialise first user and set role super admin
        if(!($this->userRepository->findAll())){

	        if(!$roleSuperAdmin){
	        	$roleAdminObject->setName('ROLE_SUPER_ADMIN');
	        	$em->persist($roleAdminObject);
	        }

	        if(!$roleUser){
	        	$roleUserObject->setName('ROLE_USER');
	        	$em->persist($roleUserObject);
	        }
	        $em->flush();
	        $user->setRoles($this->userRepository->findRoleUser(['name'=>'ROLE_SUPER_ADMIN']));
        }else {
            $user->setRoles($this->userRepository->findRoleUser(['name'=>'ROLE_USER']));
        }
        $this->userRepository->createUser($user);

        //-- send user confirmation email
		try{
			$verifyEmail = $this->sendEmailService->verifyRegistrationEmail($user);
		}   catch (Exception $e){
			throw new Exception($e->getMessage());
		}
        if($verifyEmail){
            return "New user " . $user->getUsername() . " successful created !
			Please visit your email address : " . $user->getEmail() . " for confirm registration !" ;
        } else {
        	throw  new Exception('User unable registered !');
        }
    }


	/**
	 * @param array $formData
	 *
	 * @return string
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
	public function forgotPassword(array $formData) {
		if ( filter_var($formData['email'],FILTER_SANITIZE_EMAIL) ) {
			$email    = $formData['email'];
		} else {
		  throw new Exception('Invalid user data!');
        }

		$userObject = $this->em->getRepository( User::class )->findOneBy( array( 'email' => $email ) );
		if ( !isset($userObject) || $email !== $userObject->getEmail() ) {
			throw  new Exception( 'No user with this email: ' . $email . '!' );
		}
		 //-- generate new random password
		$randomPassword = substr( $userObject->getPassword(), 8, 8 );
		//-- hashing random password
		$password       = $this->passwordEncoder->encodePassword( $userObject, $randomPassword );

		$userObject->setPassword( $password );
		//-- send email with new password
		$this->sendEmailService->forgotPasswordEmail( $randomPassword, $userObject );

		$this->em->persist( $userObject );
		$this->em->flush();
		return 'Your new password is sent to your email: ' . $email . ' !';
	}


	/**
	 * @param User $user
	 *
	 * @throws \Exception
	 */
	public function editUser(User $user){
		$user->setDateEdit(new \DateTime('now'));
		$this->userRepository->updateUser($user);
	}

	/**
	 * @param User $user
	 *
	 * @return bool
	 */
	public function removeUser( User $user ) {
		if($this->userRepository->deleteUser($user)){
			return true;
		}
		return false;
	}

	/**
	 * @param User $user
	 *
	 * @return string
	 */
	public function changePassword(User $user){
		if( filter_var($user->getOldPassword(),FILTER_SANITIZE_STRING )
		   && filter_var($user->getPlainPassword(),FILTER_SANITIZE_STRING )){
			$oldPassword = $user->getOldPassword();
			$newPassword = $user->getPlainPassword();
		} else {
			throw new Exception('Invalid user data !');
		}
        if($this->passwordEncoder->isPasswordValid($user,$oldPassword)) {
            $password = $this->passwordEncoder
                ->encodePassword($user,$newPassword);
            $em = $this->em;
            $user->setPassword($password);
            $em->persist($user);
            $em->flush();
            return 'Password change successful !';
        } else {
            throw new Exception('Old password mishmash !');
        }
    }


	/**
	 * @return mixed
	 * @throws \Exception
	 */
	public function listAllUsers() {
		try {
			$query = $this->userRepository->findAllUsers();
			$request = $this->request->getCurrentRequest();
			$pagination = $this->paginator->paginate(
				$query,
				$request->query->getInt('page', 1),
				5
			);
			return $pagination;
		} catch ( \Exception $e ) {
			throw  new \Exception($e->getMessage());
		}
	}
}