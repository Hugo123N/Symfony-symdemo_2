<?php

namespace App\Controller\Cms;

use App\Service\Cms\CategoriesService;

use App\Validation\ValidatedRequest;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
// /**
//     * Require ROLE_ADMIN for *every* controller method in this class.
//     *
//     * @IsGranted("ROLE_ADMIN")
// */

class CategoriesController extends AbstractController
{
    /**
        * Require ROLE_ADMIN for only this controller method.
        *
        * @IsGranted("ROLE_ADMIN")
    */

    private $categories;

    public function __construct(CategoriesService $categories){
        $this->categories = $categories;
    }


    /**
     * @Route("cms/categories/list-categories", name="listCategories")
     */
    public function index()
    {
        $categories = $this->categories->getAll();

        return $this->render('cms/categories/listCategories.html.twig', [
            'categories' => $categories
        ]);
    }



    /**
     * @Route("cms/categories/create-categories", name="showCreateCategories")
     */
    public function showCreate()
    {
        return $this->render('cms/categories/createCategories.html.twig');
    }
   
    /**
     * @Route("cms/categories/createAction", name="createCategories")
     */
    public function createCategories(Request $request)
    {
        $data = $request->request->all(); //lấy dl từ form bỏ vào object data.

        if (!ValidatedRequest::isValidatedname($data)) {
            return $this->json($this->notificationFailed());
        }

        $result = $this->categories->createCate($data); // đưa dl đến services để xli

        return $this->json($result);
    }





    /**
     * @Route("cms/categories/edit-Categories/{id}", name="showEditCategories", methods={"GET"})
     */
    public function showEdit(int $id)
    {
        $catagoriesById = $this->categories->get($id);

        return $this->render('cms/categories/editCategories.html.twig', [
            'categoriesById' => $catagoriesById
        ]);
    }

    /**
     * @Route("cmscategories/editAction", name="editCategories")
     */
    public function editCategories(Request $request)
    {
        $data = $request->request->all();

        if (!ValidatedRequest::isValidatedName($data)) {
            return $this->json($this->notificationFailed());
        }

        $result = $this->categories->editCate($data);

        return $this->json($result);
    }




    /**
     * @Route("cms/categories/remove-categories/{id}", name="removeCategories", methods={"GET"})
     */
    public function removeCategories(int $id)
    {   
        $this->categories->removeCategories($id);

        return $this->redirectToRoute('listCategories');
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
