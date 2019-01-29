<?php
/**
 * Created by PhpStorm.
 * User: AngelRobertov
 * Date: 21.11.2017 г.
 * Time: 9:12
 */

namespace AppBundle\Services;


use AppBundle\Entity\User;

use ShopBundle\Entity\ClientOrder;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Config\Definition\Exception\Exception;



class SendEmailService implements SendEmailServiceInterface
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
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
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

	/**
	 * @param $randomPassword
	 * @param User $user
	 *
	 * @return bool
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
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

	/**
	 * @param ClientOrder $order
	 * @param User $user
	 *
	 * @return string
	 */
	public function sendOrderConfirmEmail(ClientOrder $order,User $user): ?string {
	    try {
		    $message = ( new \Swift_Message( 'Bootshop order details' ) )
			    ->setFrom( $this->adminEmail )
			    ->setTo( $user->getEmail() )
			    ->setBody(
				    $this->template->render( '@Shop/Email/confirm_order.htm.twig',[
				    	'order'=>$order
				    ] ),'text/html'
			    );
		    $this->mailer->send($message);
		    return 'Вe have sent you an email to '. $user->getEmail().' describing your order !';
	    } catch ( \Twig_Error_Loader $e ) {
	    } catch ( \Twig_Error_Runtime $e ) {
	    } catch ( \Twig_Error_Syntax $e ) {
	    }
    }

}