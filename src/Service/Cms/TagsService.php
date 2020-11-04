<?php

namespace App\Service\Cms;

use App\Entity\Tags;
use App\Repository\TagsRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class TagsService
{
    private $enityManagerInterface;
    private $tagsRepository;

    public function __construct(
        EntityManagerInterface $enityManagerInterface,
        TagsRepository $TagsRepository
    )
    {
        $this->enityManagerInterface = $enityManagerInterface;
        $this->tagsRepository = $TagsRepository;
    }


    // get all tags:
    public function getAll()
    {
        return $this->tagsRepository->findAll();
    }
    // lay data by id:
    public function getById($id)
    {
        return $this->tagsRepository->find($id);
    }


    // create Tags:
    public function createTags($data)
    {
        try {
            $tag = new tags(); //tạo 1 đối tượng object theo entity

            $tag->setTagName($data['name']); // gán giá trị data vào content của đối tượng Categories
            $tag->setUpdatedAt(new \DateTime('now'));
            $tag->setCreatedAt(new \DateTime('now'));

            $this->enityManagerInterface->persist($tag);
            $this->enityManagerInterface->flush();

            return [
                'status' => 'success',
                'message' => 'success create',
                'url' =>'/tags/list-tags',
                'error' => $tag->getId()
            ];

        } catch (\Exception $ex) {
            return [
                'status' => 'failed',
                'message' => 'failed in create',
                'error' => $ex->getMessage()
            ];
        }
    }


    public function editTag($data)
    {
        try {
            $tag = $this->tagsRepository->find($data['id_tag']);

            $tag->setTagName($data['name']);
            $tag->setUpdatedAt(new \DateTime('now'));

            $this->enityManagerInterface->persist($tag);
            $this->enityManagerInterface->flush();

            return [
                'status' => 'success',
                'message' => 'success at edit',
                'url' =>'/cms/tags/list-tags',
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


    // remove tags:
    public function removeTag($data)
    {
        try {
            $tag = $this->tagsRepository->find($data['id_tag']);

            $this->enityManagerInterface->remove($tag);
            $this->enityManagerInterface->flush();

            return [
                'status' => 'success',
                    'message' => 'success remove',
                    'url' =>'/cms/tags/list-tags',
                    'error' => $tag->getId()
                ];

        } catch (\Throwable $th) {

            return [
                'status' => 'failed',
                'message' => 'failed in remove',
                'error' => $th->getMessage()
            ];
            
        }
    }


}