<?php

namespace App\Controller\Api;

use App\DTO\Request\ReCaptcha\ReCaptchaRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReCaptchaController extends AbstractController
{
    private $session;

    public function __construct()
    {
        return true;
    }

    // validate GG reCaptcha
    public function reCaptcha(Request $request, ReCaptchaRequest $reCaptchaRequest, ValidatorInterface $validator)
    {
        $reCaptchaRequest->buildByRequest($request);
        $errors = $validator->validate($reCaptchaRequest);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            return $this->json([
                'notificate' => ['status' => 'failed', 'messages' => $messages]
            ]);
        }
    }
}
