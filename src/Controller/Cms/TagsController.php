<?php

namespace App\Controller\Cms;

use App\Service\Cms\TagsService;

use App\Validation\ValidatedRequest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TagsController extends AbstractController
{
    private $tags;

    public function __construct(TagsService $tagsService){
        $this->tags = $tagsService;
    }

    /**
     * @Route("cms/tags/list-tags", name="listTags")
     */
    public function index()
    {
        $result = $this->tags->getAll();

        return $this->render('cms/tags/listTags.html.twig', [
            'tags' => $result,
        ]);
    }

    /**
     * @Route("cms/tags/create-tags", name="showCreateTags")
     */
    public function showCreate()
    {
        return $this->render('cms/tags/createTags.html.twig');
    }
   
    /**
     * @Route("cms/tags/createAction", name="createTags")
     */
    public function createTags(Request $request)
    {
        $data = $request->request->all(); //lấy dl từ form bỏ vào object data.

        if (!ValidatedRequest::isValidatedname($data)) {
            return $this->json($this->notificationFailed());
        }

        $result = $this->tags->createTags($data);

        return $this->json($result);
    }



    /**
     * @Route("cms/tags/edit-tags/{id}", name="showEditTags", methods={"GET"})
     */
    public function showedit(int $id)
    {
        $result = $this->tags->getById($id);
        //dd($result);
        return $this->render('cms/tags/editTags.html.twig', [
            'tagById' => $result,
        ]);
    }
    /**
     * @Route("cms/tags/editAction", name="editTags")
     */
    public function editTags(Request $request)
    {
        $data = $request->request->all(); //lấy dl từ form bỏ vào object data.

        $result = $this->tags->editTag($data);

        return $this->json($result);
    }


    /**
     * @Route("cms/tags/removeAction", name="removeTags", methods={"POST"})
     */
    public function removeTag(Request $request)
    {
        $data = $request->request->all();

        $result = $this->tags->removeTag($data);

        return $this->json($result);
    }

    // notification failed:
    private function notificationFailed()
    {
        return [
            'status' => 'failed',
            'message' => 'failed at validated',
            'error' => 'error'
        ];
    }


}