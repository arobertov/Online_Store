<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
	/**
	 * @Route("/login",name="login")
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function loginAction()
    {
    	return $this->redirectToRoute('home_page');
    }

}
