<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/{_locale}/admin/users", name="users")
     */
    public function index()
    {

        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/{_locale}/admin/users/show/{user}", name="user_show")
     */
    public function show(User $user)
    {
        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/{_locale}/admin/users/add", name="user_add")
     */
    public function add(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword(
                $encoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $picture = $form->get('picture')->getData();
            $newFilename = $user->getFirstname() . $user->getLastname() . '-' . uniqid() . '.' . $picture->guessExtension();
            $picture->move(
                $this->getParameter('images_directory'),
                $newFilename
            );
            $user->setPicture($newFilename);
            $user->setRoles(['ROLE_UNVERIFIED_USER']);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('users');
        } else {
            return $this->render('user/add.html.twig', [
                'form' => $form->createView(),
                'errors' => $form->getErrors()
            ]);
        }
    }

    /**
     * @Route("/{_locale}/user/delete/{user}", name="user_delete")
     */
    public
    function delete(User $user, EntityManagerInterface $em)
    {
        if ($user->getId() !== $this->getUser()->getId()) {
            $em->remove($user);
            $em->flush();
        }
        return $this->redirectToRoute('users');
    }

}
