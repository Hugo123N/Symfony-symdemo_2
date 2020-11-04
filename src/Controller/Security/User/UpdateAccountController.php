<?php

namespace App\Controller\Security\User;

use App\Validation\ValidatedRequest;
use App\Security\User\Account\UpdateAccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateAccountController extends AbstractController
{
    private $updateAccountService;

    public function __construct(UpdateAccountService $updateAccountService){
        $this->updateAccountService = $updateAccountService;
    }
    /**
     * @Route("/update-account", name="app_update_account")
     */
    public function updateIndex()
    {
        return $this->render('security/update.html.twig');
    }

    /**
     * @Route("/update-action", name="updateAccount")
     */
    public function updateAccount(Request $request)
    {
        $data = $request->request->all();
        // dd($data);
        if (!ValidatedRequest::isValidatedPassUpdate($data)) {
            return $this->json($this->notificationFailed());
        }
        $result = $this->updateAccountService->checkPassAndUpdate($data);

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