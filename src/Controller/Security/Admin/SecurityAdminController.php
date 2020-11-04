<?php

namespace App\Controller\Security\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityAdminController extends AbstractController
{
    /**
     * @Route("/admin/login", name="app_login_admin")
     */
    public function loginAdmin(AuthenticationUtils $authenticationUtils): Response
    {
        // kiem tra ton tai tk + admin:
        if ($this->getUser() && $this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('listCategories');
        }

        // gap loi dang nhap (neu co)
        $error = $authenticationUtils->getLastAuthenticationError();
        // ten nguoi dung cuoi cung phai la ten tu nhap
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/admin/loginAdmin.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/admin/logout", name="app_logout_admin")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    
    
}
