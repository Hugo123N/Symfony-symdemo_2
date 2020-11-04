<?php

namespace App\Security\User\Account;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UpdateAccountService
{
    private $passwordEncoder;
    private $entityManager;
    private $userRepository;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManagerInterface,
        UserRepository $userRepository
    )
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManagerInterface;
        $this->userRepository = $userRepository;
    }
    
    // Action update:
    public function checkPassAndUpdate($data)
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);

        if ($this->passwordEncoder->isPasswordValid($user, $data['old_password'])) 
        {
            $user->setPassword( $this->passwordEncoder->encodePassword($user, $data['new_password']) );

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return [
                'status' => 'success',
                'message' => 'success update account',
                'url' => '/login',
                'error' => 'no error'
            ];
        }
        return [
            'status' => 'failed',
            'message' => 'your old password is valid, please try again!',
            'error' => 'error'
        ];
    }

}