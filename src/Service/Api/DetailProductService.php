<?php

namespace App\Service\Api;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use App\Entity\Products;
use App\Repository\ProductsRepository;
use App\Repository\TagsRepository;
use Doctrine\ORM\EntityManagerInterface;

class DetailProductService
{
    private $entityManagerInterface;

    private $categoriesRepository;
    private $productsRepository;
    private $tagsRepository;

    public function __construct(
        EntityManagerInterface $entityManagerInterface,

        CategoriesRepository $categoriesRepository,
        ProductsRepository $productsRepository,
        TagsRepository $tagsRepository
    )
    {
        $this->entityManagerInterface = $entityManagerInterface;

        $this->categoriesRepository = $categoriesRepository;
        $this->productsRepository = $productsRepository;
        $this->tagsRepository = $tagsRepository;
    }


    //get all data of product:
    public function getAll($id)
    {
        return $this->productsRepository->find($id);
    }

    // get tags of this product:
    public function getTagsOfProduct($id)
    {
        $product = $this->productsRepository->find((int) $id);
        $tagsOfProduct = $product->getTags();
        $nameTagsOfProduct = [];

        foreach ($tagsOfProduct as $tag) {
            $nameTagsOfProduct[] = $tag->getTagName();
        }

        return $nameTagsOfProduct;
        
    }
    
}