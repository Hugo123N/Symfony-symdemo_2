<?php

namespace App\Service\Cms;
use App\Repository\ComnentRepository;

use App\Entity\Comnent;
use Doctrine\ORM\EntityManagerInterface;

class ComnentService
{
    private $comnentRepository;
    private $entityManager;
    
    public function __construct(ComnentRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->comnentRepository = $repository;
        $this->entityManager = $entityManager;
    }

    public function create($data)
    {
        $comment = new Comnent(); //tạo 1 đối tượng object theo entity
        $comment->setUserName($data['name']); // gán giá trị data vào content của đối tượng comment
        $comment->setEmail($data['email']); // gán giá trị data vào content của đối tượng comment
        $comment->setSubJect($data['subject']); // gán giá trị data vào content của đối tượng comment
        $comment->setStatus(1); // gán giá trị data vào content của đối tượng comment
        $comment->setCreatedAt(new \DateTime('now'));
        $comment->setUpdatedAt(new \DateTime('now'));
        $comment->setContent($data['comments']); // gán giá trị data vào content của đối tượng comment

        // $entityManager = $this->getDoctrine()->getManager(); //làm việc với dl -> nếu xli dl bên controller

        $this->entityManager->persist($comment); // giữ gtrị đối tượng comment
        $this->entityManager->flush(); // đưa dl vào db đã đc persist
    }

}

