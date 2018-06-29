<?php
/**
 * Created by PhpStorm.
 * User: AngelRobertov
 * Date: 21.11.2017 г.
 * Time: 9:12
 */

namespace AppBundle\Services;


use AppBundle\Entity\User;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Config\Definition\Exception\Exception;



class SendEmailService
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var string
     */
    private $adminEmail;

    /**
     * @var
     */
    private $template;

	/**
	 * SendEmailService constructor.
	 *
	 * @param \Swift_Mailer $mailer
	 * @param $adminEmail
	 * @param \Twig_Environment $templating
	 */
    public function __construct(\Swift_Mailer $mailer,$adminEmail, \Twig_Environment $templating)
    {
        $this->mailer = $mailer;
        $this->adminEmail = $adminEmail;
        $this->template = $templating;
    }

	/**
	 * @param User $user
	 *
	 * @return bool
	 */
    public function verifyRegistrationEmail(User $user){
        $message = (new \Swift_Message('New user registration'))
            ->setFrom($this->adminEmail)
            ->setTo($user->getEmail())
            ->setBody(
                $this->template->render('@App/Emails/registration_email.html.twig',[
                  'user'=>$user
                ]),
                'text/html'
            );
        if($this->mailer->send($message)){
        	return true;
        } else {
        	throw new Exception('Cannot send email !');
        }
    }

    public function forgotPasswordEmail($randomPassword,User $user){
	    $message = (new \Swift_Message('Send new password'))
		    ->setFrom($this->adminEmail)
		    ->setTo($user->getEmail())
		    ->setBody(
			    $this->template->render('@App/Emails/forgot_password_email.html.twig',[
				    'user' => $user,
				    'password' => $randomPassword
			    ]),
			    'text/html'
		    );
	    $this->mailer->send($message);
	    return true;
    }

}