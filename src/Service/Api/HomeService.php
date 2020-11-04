<?php

namespace App\Service\Api;

// tro toi entity+repository
use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use App\Entity\Products;
use App\Repository\ProductsRepository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\All;

class HomeService
{   private $entityManagerCategories;
    private $categoriesRepository;

    private $entityManagerProducts;
    private $productsRepository;

    public function __construct(
        EntityManagerInterface $entityManagerCategories,
        CategoriesRepository $categoriesRepository,

        EntityManagerInterface $entityManagerProducts,
        ProductsRepository $productsRepository
    )
    {
        $this->entityManagerCategories = $entityManagerCategories;
        $this->categoriesRepository = $categoriesRepository;

        $this->entityManagerProducts = $entityManagerProducts;
        $this->productsRepository = $productsRepository;
    }


    // get limit products c1:
    public function getLimitPro()
    {
        $queryBuilder = $this->productsRepository->createQueryBuilder('p');
        // dd($queryBuilder);
        $queryBuilder ->select()
            ->setMaxResults(6)
            ->orderBy('p.id', 'ASC');
        $result = $queryBuilder->getQuery()->getResult();
        return $result;
    }
     // get limit products c2:
     public function getLimitProo()
     {
         $result = $this->productsRepository->findBy(array (), array('name' => 'ASC'),4,0);
         return $result;
     }

     

    // get limit product by categories:
    public function getLimitProByCate($data)
    {
        $category_id = $data['category_id'];

        $queryBuilder = $this->productsRepository->createQueryBuilder('p');
        $queryBuilder ->select()
            ->join('p.category', 'c')
            ->where('c.categoryId = : category_id')
            ->setParameter('category)id', $category_id)
            ->setMaxResults(6)
            ->orderBy('p.id', 'ASC');
        return $this->$queryBuilder->getQuery()->getResult();
    }
    

}