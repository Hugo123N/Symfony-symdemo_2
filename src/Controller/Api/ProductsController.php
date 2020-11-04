<?php

namespace App\Controller\Api;

use App\Service\Api\ProductsService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;

class ProductsController extends AbstractController
{
    private $products;

    public function __construct(ProductsService $ProductsService)
    {
        $this->products = $ProductsService;
    }
    

    /**
     * @Route("api/all-products", name="api/allProducts")
     */
    public function index()
    {
        $resultPro = $this->products->getAllPro();
        $resultCate = $this->products->getAllCate();
        return $this->render('api/products/index.html.twig', [
            'allProducts' => $resultPro,
            'allCategories' => $resultCate,
        ]);
    }

    /**
     * @Route("api/list-products-by-categories", name="api/productsByIdCate")
     */
    public function getProByIdCategory(Request $request)
    {
        $data = $request->request->all();
        // dd($data);
        $result = $this->products->getProByIdCate($data);

        $html = $this->renderView('api/products/contentProducts.html.twig', ['allProducts' => $result]);
        // dd($html);
        return $this->json([
            'status' => 'success',
            'html' => $html,
        ]);
    }

    /**
     * @Route("api/list-products-by-price", name="api/productsByPrice")
     */
    public function getProByPrice(Request $request)
    {
        $data = $request->request->all();

        $result = $this->products->getProByIdPrice($data);

        $html = $this->renderView('api/products/contentProducts.html.twig', ['allProducts' => $result]);

        return $this->json([
            'status' => 'success',
            'html' => $html,
        ]);
    }

    /**
     * @Route("api/search-products", name="api/searchProducts")
     */
    public function searchProducts(Request $request)
    {
        $data = $request->request->all();
        //dd($data);
        $result = $this->products->searchPro($data);
        $resultCate = $this->products->getAllCate();
        return $this->render('api/products/index.html.twig', [
            'allProducts' => $result,
            'allCategories' => $resultCate,
        ]);
    }
}
