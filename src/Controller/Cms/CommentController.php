<?php

namespace App\Controller\Cms;


use App\Service\Cms\ComnentService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

class CommentController extends AbstractController
{
    private $comnentService;
    // private $comments;
    public function __construct(ComnentService $comnentService)
    {
        $this->comnentService = $comnentService;
    }
    /**
     * @Route("/comment", name="comment")
     */
    public function index(Request $request)
    {
        $data = $request->request->all(); // lấy dl từ request đưa vào $data
        
        $this->comnentService->create($data); //đưa dữ liệu đến service


        return $this->render('cms/comment/index.html.twig', [
            'controller_name' => 'abc'
        ]);
    }
}
