<?php

namespace App\Service\Api;

// tro toi entity+repository
use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use App\Entity\Products;
use App\Repository\ProductsRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\All;

class ProductsService
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


    // get all products:
    public function getAllPro()
    {
        return $this->productsRepository->findAll();
    }

    // get limit products c1:
    public function getLimitPro()
    {
        $queryBuilder = $this->productsRepository->createQueryBuilder('p');
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



     // get all categories:
    public function getAllCate()
    {
        return $this->categoriesRepository->findAll();
    }

     

    // get limit product by categories:
    // public function getLimitProByCate($data)
    // {
    //     $category_id = $data['category_id'];

    //     $queryBuilder = $this->productsRepository->createQueryBuilder('p');
    //     $queryBuilder ->select()
    //         ->join('p.category', 'c')
    //         ->where('c.categoryId = : category_id')
    //         ->setParameter('category)id', $category_id)
    //         ->setMaxResults(6)
    //         ->orderBy('p.id', 'ASC');
    //         $result = $queryBuilder->getQuery()->getResult();

    //     return $result;
    // }


    public function getProByIdCate($data)
    {
        $id = $data['id_category'];
        $queryBuilder = $this->productsRepository->createQueryBuilder('p');
        $queryBuilder->select()
        ->join('p.category', 'c')
        ->where('c.id = :id_category')
        ->setParameter('id_category', $id)
        ->orderBy('p.name', 'ASC');

        $result = $queryBuilder->getQuery()->getResult();
        
        return $result;
    }


    public function getProByIdPrice($data)
    {
        $queryBuilder = $this->productsRepository->createQueryBuilder('p');
        $queryBuilder->select();

        if (isset($data['checkbox'])) {
            $queryBuilder
            ->orderBy('p.name', 'ASC');
        } else {
            // "SELECT * FROM products WHERE (price > 1000000 AND price <= 3000000) OR (price > 3000000 AND price <= 3000000)";

            if (isset($data['checkbox1'])) {
                $queryBuilder
                ->orWhere('(p.price >= :min1 AND p.price <= :max1)')
                ->setParameter('min1', 1000000)
                ->setParameter('max1', 3000000);
            }
            if (isset($data['checkbox2'])) {
                $queryBuilder
                ->orWhere('(p.price > :min2 AND p.price <= :max2)')
                ->setParameter('min2', 4000000)
                ->setParameter('max2', 7000000);
            }
            if (isset($data['checkbox3'])) {
                $queryBuilder
                ->orWhere('(p.price > :min3 AND p.price <= :max3)')
                ->setParameter('min3', 7000000)
                ->setParameter('max3', 12000000);
            }
            if (isset($data['checkbox4'])) {
                $queryBuilder
                ->orWhere('p.price > :min4')
                ->setParameter('min4', 12000000);
            }
            
            $queryBuilder
            ->orderBy('p.name', 'ASC');
        }
        $queryBuilder
        ->orderBy('p.name', 'ASC');

        $result = $queryBuilder->getQuery()->getResult();
        // dd($result);
        return $result;
    }


    // search products:
    public function searchPro($data)
    {
        $queryBuilder = $this->productsRepository->createQueryBuilder('p');
        $queryBuilder->select();
        $keywords = $data['search_content'];
        if (!empty($keywords)) {
            $queryBuilder
            ->where('p.name LIKE :keywords')
            ->setParameter('keywords', "%$keywords%");
        }

        $queryBuilder->orderBy('p.id', 'ASC');

        $result = $queryBuilder->getQuery()->getResult();

        return $result;
    }


}