<?php

// src/Controller/SecurityController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use Symfony\Component\Security\Core\Security;

use AppBundle\Ext\loggerClass;

class SecurityController extends Controller
{
	
	/**
     * @Route("/login/", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
		  // get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();

		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();

		// loggerClass::writeLog($error);
		// loggerClass::writeLog($request);
		// exit;
		
		return $this->render('pages/login.html.twig', array(
			'last_username' => $lastUsername,
			// 'last_username' => $request->getSession()->get(Security::LAST_USERNAME),
			'error'         => $error,
		));
		
    }//END
	
}

?>