<?php

namespace App\Controller\Api;

use App\Service\Api\HomeService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    private $home;

    public function __construct(HomeService $homeService)
    {
        $this->home = $homeService;
    }


    /**
     * @Route("/trans", name="trans")
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function translator(TranslatorInterface $translator): Response
    {
        $translated = $translator->trans('text.message', [], null, 'sk');

        return new Response($translated);
    }

    /**
     * @Route({
     *      "en": "/",
     *      "vi": "/vi/trang-chu"
     * }, name="home")
     */
    public function index()
    {
        $resultPro = $this->home->getLimitProo();
        // $resultCate = $this->home->getAllCate();
        return $this->render('api/home/index.html.twig', [
            'allProducts' => $resultPro,
            //'allCategories' => $resultCate,
        ]);
    }
}
