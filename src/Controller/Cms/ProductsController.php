<?php

namespace App\Controller\Cms;

// validated
use App\Validation\ValidatedRequest;
use App\Validation\ValidatedUpload;

// ho tro duong dan theo invỉoment
use App\Helper\UploadHelper;

use App\Service\Cms\ProductsService;
use App\Service\Cms\CategoriesService;
use App\Service\Cms\TagsService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductsController extends AbstractController
{
    
    private $products;
    private $categories;
    private $tags;

    public function __construct(
        ProductsService $products,
        CategoriesService $catagories,
        TagsService $tagsService
    ) {
        $this->products = $products;
        $this->categories = $catagories;
        $this->tags = $tagsService;
    }

    /**
     * @Route("cms/products/list-products", name="listProducts")
     */
    public function index()
    {   
        // check admin:
        
        $products = $this->products->getAll();

        return $this->render('cms/products/listProducts.html.twig', [
            'products' => $products,
        ]);
    }



    /**
     * @Route("cms/products/create-products", name="Products")
     */
    // dung de tao router toi trang create product
    public function showCreate()
    {
        $categories = $this->categories->getAll();
        $tags = $this->tags->getAll();
        
        return $this->render('cms/products/createProducts.html.twig', [
            'categories' => $categories,
            'tags' => $tags
        ]);
    }

    /**
     * @Route("cms/createActions", name="createProducts")
     */
    public function createProducts(Request $request)
    {
        $files = $request->files->all();
        $imageFile = $files['images'];
        $data = $request->request->all();

        if (!ValidatedRequest::isValidatedName($data) ||
            !ValidatedRequest::isValidatedPrice($data) || 
            !ValidatedRequest::isValidatedCheckbox($data) ||

            !ValidatedUpload::validateImageCreate($imageFile))
        {
            return $this->json($this->notificationFailed());
        }

        $data['image_name'] = ValidatedUpload::uploadImage($imageFile);

        $result = $this->products->createPro($data);

        return $this->json($result);
    }




    /**
     * @Route("cms/products/edit-products/{id}", name="showEdit", methods={"GET"})
     */
    // dung de tao router toi trang create product
    public function showEdit(int $id)
    {
        $conditions = ['id' => $id];

        $products = $this->products->getOneBy($conditions);
        $idTagsOfProduct = $this->products->getTagsOfProducts($products);
        $categories = $this->categories->getAll();
        
        $tags = $this->tags->getAll();
        
        return $this->render('cms/products/editProducts.html.twig', [
            'products' => $products,
            'idTagsOfProduct' => $idTagsOfProduct,
            'categories' => $categories,
            'tags' => $tags
        ]);
    }
    /**
     * @Route("cms/edit-products", name="editProducts")
     */
    public function editProducts(Request $request)
    {
        $data = $request->request->all();
        $files = $request->files->all();

        $imageFile = $files['images'];
        $product_image_text  = $data['product_image_text'];

        if (!ValidatedRequest::isValidatedName($data) ||
            !ValidatedRequest::isValidatedPrice($data) || 
            !ValidatedRequest::isValidatedCheckbox($data)
            ) {

            return $this->json($this->notificationFailed());
        }

        if (!empty($imageFile)) {
            if (ValidatedUpload::validateImageEdit($imageFile)) {
                $data['image_name'] = ValidatedUpload::uploadImage($imageFile);
            }
            else {
                return $this->json([
                    'status' => 'failed',
                    'message' => 'failed uppload file at edit',
                    'error' => 'no error'
                ]);
            }
        }
        else {
            $data['image_name'] = $product_image_text;
        }

        $result = $this->products->editPro($data);

        return $this->json($result);
    }





    /**
     * @Route("cms/removeAction", name="removeProducts")
     */
    public function removeProducts(Request $request)
    {
        $data = $request->request->all();
        $id_product = $data['id_product'];

        $result = $this->products->removePro($id_product);

        return $this->redirectToRoute('listProducts', $result);
    }



    /**
     * @Route("cms/search-products", name="searchProducts")
     */
    public function searchProducts(Request $request)
    {
        $data = $request->request->all();

        // $searchOption = $data['searchOption'];

        $result = $this->products->searchPro($data);

        $html = $this->renderView('cms/products/product_rows.html.twig', [
            'products' => $result,
            'product_upload_image_folder' => $_ENV['BASE_UPLOAD_FOLDER'] . '/' . $_ENV['PRODUCT_UPLOAD_FOLDER'] . '/' . $_ENV['PRODUCT_IMAGE_FOLDER'],
        ]);

        return $this->json([
            'status' => 'success',
            'html' => $html,
        ]);
    }



    // notification failed:
    private function notificationFailed()
    {
        return [
            'status' => 'failed',
            'message' => 'failed at validate',
            'error' => 'error'
        ];
    }
}
