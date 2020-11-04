<?php

namespace App\Service\Cms;

use App\Helper\UploadHelper;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;

use App\Entity\Products;
use App\Repository\ProductsRepository;

use App\Entity\Tags;
use App\Repository\TagsRepository;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;


class ProductsService
{
    private $productsService;
    private $entityManagerProducts;
    private $categoriesRepository;
    private $productsRepository;
    private $tagsRepository;

    public function __construct(
        ProductsRepository $repository,
        EntityManagerInterface $entityManager,
        CategoriesRepository $categoriesRepository,
        ProductsRepository $productsRepository,
        TagsRepository $tagsRepository
    ) {
        $this->productsService = $repository;
        $this->entityManagerProducts = $entityManager;
        $this->categoriesRepository = $categoriesRepository;
        $this->productsRepository = $productsRepository;
        $this->tagsRepository = $tagsRepository;
    }


    // lay tat ca dlieu
    public function getAll()
    {
        return $this->productsRepository->findAll();
    }
    // lay tat ca dlieu
    public function getById($id)
    {
        return $this->productsRepository->find($id);
    }
    // lay dlieu theo thuoc tinh:
    public function getOneBy(array $conditions)
    {
        return $this->productsRepository->findOneBy($conditions);
    }

    // them 1 products moi:
    public function createPro($data)
    {
        try {
            $pro = new Products(); //tạo 1 đối tượng object theo entity

            $category = $this->categoriesRepository->find((int) $data['category_id']);

            $pro->setName($data['name']); // gán giá trị data vào content của đối tượng prod
            $pro->setPrice($data['price']);
            $pro->setImages($data['image_name']);
            $pro->setDescription($data['description']);
            $pro->setSlug('auto');
            $pro->setPriority($data['priority']);
            $pro->setStatus($data['status']);
            $pro->setUpdatedAt(new \DateTime('now')); 
            $pro->setCreatedAt(new \DateTime('now'));
            // mối quan hệ với categories
            $pro->setCategory($category);
            // them tags theo quan he ManyToMany:
            if (!empty($data['checkboxTagName'])) {
                foreach ($data['checkboxTagName'] as $tagId) {
                    $tag = $this->tagsRepository->find((int) $tagId);
                    $pro->addTag($tag);
                }
            }
            else {
                return false;
            }

            $this->entityManagerProducts->persist($pro);
            $this->entityManagerProducts->flush();

            return $this->notificationSuccess();

        } catch (\Exception $ex) {
            return [
                'status' => 'failed',
                'error' => get_class($ex) . ': ' .$ex->getMessage(),
            ];
        }
    }


    // edit products:
    public function editPro($data)
    {
        try {
            $id_product = $data['id_product'];
            $pro = $this->productsRepository->find((int) $id_product); //tạo 1 đối tượng object theo entity

            $category = $this->categoriesRepository->find((int) $data['category_id']);

            $pro->setName($data['name']); // gán giá trị data vào content của đối tượng prod
            $pro->setPrice($data['price']);
            $pro->setImages($data['image_name']);
            $pro->setDescription($data['description']);
            $pro->setSlug('auto');
            $pro->setPriority($data['priority']);
            $pro->setStatus($data['status']);
            $pro->setUpdatedAt(new \DateTime('now'));
            $pro->setCreatedAt(new \DateTime('now'));
            // mối quan hệ với categories
            $pro->setCategory($category);

            // edit tag theo quan he ManyToMany:
            $product = $this->getById($data['id_product']);
            $tags = $product->getTags();
            foreach ($tags as $tag){
                $tag = $this->tagsRepository->find((int) $tag->getId());
                $pro->removeTag($tag);
            }

            if (!empty($data['checkboxTagName'])) {
                foreach ($data['checkboxTagName'] as $tagId) {
                    $tag = $this->tagsRepository->find((int) $tagId);
                    $pro->addTag($tag);
                }
            }
            else {
                return false;
            }

            $this->entityManagerProducts->persist($pro);
            $this->entityManagerProducts->flush();

            return $this->notificationSuccess();
        } catch (\Exception $ex) {
            return [
                'status' => 'failed in service',
                'error' => get_class($ex) . ': ' .$ex->getMessage(),
            ];
        }
    }



    // xoa dlieu theo id:
    public function removePro(int $id_product)
    {
        try {
            $pro = $this->productsRepository->find((int) $id_product);

            $imageName = $pro->getImages();
            
            $this->entityManagerProducts->remove($pro);
            $this->entityManagerProducts->flush();

            $this->deleteImageFile($imageName);

            return $this->notificationSuccess();

        } catch (\Exception $ex) {
            return [
                'status' => 'failed',
                'error' => get_class($ex) . ': ' .$ex->getMessage(),
            ];
        }
    }
    private function deleteImageFile($imageName)
    {
        $dir = UploadHelper::getProductImageFolder();

        $file = $dir.DIRECTORY_SEPARATOR.$imageName;
        
        return unlink($file);
    }


    // get tags of product:
    public function getTagsOfProducts($products)
    {
        $selectedTags = $products->getTags();
        $selected_tag_ids = [];

        foreach ($selectedTags as $tag) {
            $selected_tag_ids[] = $tag->getId();
        }

        return $selected_tag_ids;
        
    }


    // search Products:
    public function searchPro($data)
    {
        $searchContent = $data['searchContent'];

        $queryBuilder = $this->productsRepository->createQueryBuilder('p');
        $queryBuilder->select();

        if (!empty($searchContent)) {
            $queryBuilder
                ->where('p.name LIKE :keyword')
                ->setParameter('keyword', "%$searchContent%")
            ;
        }

        $queryBuilder->orderBy('p.id', 'ASC');
            
        $result = $queryBuilder->getQuery()->getResult();

        return $result;
    }


// notification success:
    private function notificationSuccess()
    {
        return [
            'status' => 'success',
            'url' =>'/cms/products/list-products',
            'error' => 'no error'
        ];
    }

    
}