<?php

namespace App\Service\Cms;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;

use App\Repository\ComnentRepository;

use Doctrine\ORM\EntityManagerInterface;

class CategoriesService
{
    private $categoriesService;
    private $enityManagerCategories;
    private $categoriesRepository;

    public function __construct(
        ComnentRepository $repository, 
        EntityManagerInterface $enityManager,
        CategoriesRepository $categoriesRepository
    ){
        $this->categoriesService = $repository;
        $this->enityManagerCategories = $enityManager;
        $this->categoriesRepository = $categoriesRepository;
    }

    // lay tat ca dlieu
    public function getAll()
    {
        return $this->categoriesRepository->findAll();
    }
    // lay dlieu theo id:
    public function get($id)
    {
        return $this->categoriesRepository->find($id);
    }




    public function createCate($data)
    {
        try {
            $cate = new Categories(); //tạo 1 đối tượng object theo entity

            $cate->setName($data['name']); // gán giá trị data vào content của đối tượng Categories
            $cate->setSlug($data['slug']);
            $cate->setPriority($data['priority']);
            $cate->setStatus($data['status']);
            $cate->setUpdatedAt(new \DateTime('now'));
            $cate->setCreatedAt(new \DateTime('now'));

            $this->enityManagerCategories->persist($cate);
            $this->enityManagerCategories->flush();

            return ['status' => 'success',
                    'message' => 'success create',
                    'url' =>'/cms/categories/list-categories',
                    'error' => $cate->getId()];

        } catch (\Exception $ex) {
            return [
                'status' => 'failed in service',
                'message' => 'failed in create',
                'error' => $ex->getMessage()
            ];
        }
    }



    public function editCate($data)
    {
        try {
            $categories_id = $data['categories_id'];
            $cate = $this->categoriesRepository->find($categories_id);

            $cate->setName($data['name']);
            $cate->setSlug($data['slug']);
            $cate->setPriority($data['priority']);
            $cate->setStatus($data['status']);

            $this->enityManagerCategories->persist($cate);
            $this->enityManagerCategories->flush();

            return [
                'status' => 'success',
                'message' => 'success at edit cate',
                'url' =>'/cms/categories/list-categories',
                'error' => 'no error'
            ];
        } catch (\Throwable $th) {
            return [
                'status' => 'failed',
                'message' => 'failed at edit',
                'error' => 'error'
            ];
        }
    }



    public function removeCategories($id)
    {
        try {
            $cate = $this->categoriesRepository->find((int) $id);
            // dd($cate);
            $this->enityManagerCategories->remove($cate);
            $this->enityManagerCategories->flush();

            return [
                'status' => 'success',
                'message' => 'sucess at remove',
                'error' => 'no error'
            ];
        } catch (\Throwable $th) {
            return [
                'status' => 'failed',
                'message' => 'failed at remove',
                'error' => get_class($th) . ': ' .$th->getMessage(),
            ];
        }
    }

}