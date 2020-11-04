<?php

namespace App\Controller\Cms;

use App\Service\NewsService;
// use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DemoController extends AbstractController
{
    private $service1;

    public function __construct()
    {
        
    }

    /**
     * @Route("/demo", name="demo")
     */
    public function index()
    {
        // $news = $this->service1->getNews();
        $view = $this->renderView('cms/demo/index.html.twig', [
            'controller_name' => 'demo',
            'messages' => ['tét', 'fgfg']
        ]);

        return new Response($view);
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard()
    {
        return $this->renderView('cms/dasdboard/index.html.twig');
    }
}
