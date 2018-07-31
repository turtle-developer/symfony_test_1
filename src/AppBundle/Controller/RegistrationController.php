<?php

// src/AppBundle/Controller/RegistrationController.php
namespace AppBundle\Controller;

// use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
// use Symfony\Component\Form\FormBuilder;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationController extends Controller
{
    /**
     * @Route("/register/", name="user_registration")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) build the form
        $user = new User();
        // $form = $this->createForm(UserType::class, $user);
        $form = $this->createFormBuilder($user)
				->add('email', EmailType::class)
				->add('username', TextType::class)
				->add('plainPassword', RepeatedType::class, array(
					'type' => PasswordType::class,
					'first_options'  => array('label' => 'Password'),
					'second_options' => array('label' => 'Repeat Password'),
				))
				->getForm();;

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
			
			// $password = $passwordEncoder->encodePassword($user->getPlainPassword(), $user->getSalt());
			// $user->setPassword($password);

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('post');
        }

        return $this->render(
            'pages/register.html.twig',
            array('form' => $form->createView())
        );
    }
}

?>