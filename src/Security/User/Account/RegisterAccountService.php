<?php

namespace App\Security\User\Account;

// use App\Security\LoginFormAuthenticator;

use App\Entity\User;
use App\Repository\UserRepository;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Swift_Mailer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterAccountService
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
    // find account by username:
    public function getAccountByUserName($username)
    {
        $criteria = ['userName' => $username];
        $result = $this->userRepository->findOneBy($criteria);
        return $result;
    }

    // check account by username or email:
    public function isExistedUsernameOrEmail($data)
    {
        $queryBuilder = $this->userRepository->createQueryBuilder('u');
        $checkExist = $queryBuilder
            ->select()
            ->where('u.userName = :user_name OR u.email = :email')
            ->setParameter('user_name', $data['name'])
            ->setParameter('email', $data['email'])
            ->getQuery()
            ->getResult();

        if ( empty($checkExist)) {
            return false;
        }

        return true;
    }


    // create new account:
    public function createAccount($data)
    {
        try {
            $user = new User();

            $user->setName($data['name']);
            $user->setEmail($data['email']);
            $user->setPassword( $this->passwordEncoder->encodePassword($user, $data['password']) );
            $user->setRoles($data['roles']);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return [
                'status' => 'success',
                'message' => 'success create your account',
                'error' => 'no error'
            ];
            
        } catch (\Exception $ex) {
            return [
                'status' => 'failed',
                'message' => 'failed create your account',
                'error' => [ get_class($ex) => $ex->getMessage() ]
            ];
        }
    }

}