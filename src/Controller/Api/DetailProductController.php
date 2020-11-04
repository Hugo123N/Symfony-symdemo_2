<?php

namespace App\Controller\Api;

use App\Service\Api\DetailProductService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;

class DetailProductController extends AbstractController
{
    private $detailProduct;

    public function __construct(DetailProductService $detailProductService)
    {
        $this->detailProduct = $detailProductService;
    }

    /**
     * @Route("api/detail-product/{id}", name="api/detailProduct", methods={"GET"})
     */
    public function index(int $id)
    {
        $result = $this->detailProduct->getAll((int) $id);
        $nameTagsOfProduct = $this->detailProduct->getTagsOfProduct($id);

        return $this->render('api/detailProduct/index.html.twig', [
            'status' => 'success',
            'detailProduct' => $result,
            'nameTagsOfProduct' => $nameTagsOfProduct,
        ]);
    }

}