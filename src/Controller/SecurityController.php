<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RedefinePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/{_locale}/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->isGranted('ROLE_ADMIN')) {
             return $this->redirectToRoute('users');
         }
         else if($this->isGranted('ROLE_VERIFIED_USER')) {
             return $this->redirectToRoute('default');
         } else if($this->isGranted('ROLE_USER')) {
             return $this->redirectToRoute('redefine_password');
         }



        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/{_locale}/password/redefine", name="redefine_password")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $picture = $user->getPicture();
        $user->setPicture(
            new File($this->getParameter('images_directory').'/'.$user->getPicture())
        );
        $form = $this->createForm(RedefinePasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(['ROLE_VERIFIED_USER']);
            $user->setPicture($picture);
            $entityManager->flush();

            return $this->redirectToRoute('default');
        }

        return $this->render('security/redefinePassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{_locale}/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
