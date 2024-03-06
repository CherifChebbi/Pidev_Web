<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;
use App\Entity\User;


class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $user = $this->getUser();


        return $this->render('main/LogIn.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
        // Render the login page
       // return $this->render('main/LogIn.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    

    #[Route('/afterlogin', name: 'afterlogin')]
    public function test(UserRepository $repo, Security $security): Response
    {
        
        if ($security->isGranted('ROLE_ADMIN')) 
        {
            return $this->redirectToRoute('app_admin_index');
        }
        if ($security->isGranted('ROLE_USER')) 
        {
            return $this->render('index2.html.twig');
        }
        return $this->redirectToRoute('app_login');
    }


    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


}