<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{
	/**
	 * @Route("/login",name="login")
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function loginAction()
    {
    	return $this->redirectToRoute('finalize_shopping');

	    //return new Response('ok');
    }

}
