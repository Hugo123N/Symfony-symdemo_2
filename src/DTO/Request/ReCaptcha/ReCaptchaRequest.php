<?php

namespace App\DTO\Request\ReCaptcha;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ReCaptchaRequest
{
    /**
     * @Assert\NotBlank(
     *      message="Please check on the reCAPTCHA box."
     * )
     */
    public $reCaptchaString;
    
    /**
     *     @Assert\IsTrue(message="Robot verification failed, please try again.")
     */
    public $responseGRC;


    public function __construct()
    {
        return true;
    }

    public function buildByRequest(Request $request)
    {
        $reCaptchaString = $request->request->get('g-recaptcha-response');

        // check tick reCaptcha:
        $this->reCaptchaString = $reCaptchaString;

        if (!empty($reCaptchaString)) {
            // reCAPTCHA response verification
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$_ENV['GOOGLE_RECAPTCHA_SECRET_KEY'].'&response='.$reCaptchaString);
            // Decode JSON data
            $responseReCaptcha = json_decode($verifyResponse);
            $this->responseGRC = $responseReCaptcha->success;
        }
    }

}
