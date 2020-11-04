<?php

namespace App\Controller\Api;

use App\Helper\CaptchaHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CaptchaController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }


    /**
     * @Route("/captcha", name="render_captcha")
     */
    public function renderCaptcha(CaptchaHelper $captchaHelper)
    {
        // random string captcha:
        $permitted_chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ1234567890';
        $string_length = 4;
        $captcha_string = $captchaHelper->generateString($permitted_chars, $string_length);
        // create session captcha:
        $this->session->set('code_captcha', $captcha_string);
        // create property random image captcha:
        $imageCaptcha = $captchaHelper->createImageCaptcha();

        for ($i = 0; $i < $string_length; $i++) {
            $letter_space = 90/$string_length;
            $initial = 8;
            imagettftext(
                $imageCaptcha['image'],
                18,
                rand(-12, 12),
                $initial + $i*$letter_space,
                rand(15, 35),
                $imageCaptcha['textcolors'][rand(0, 1)],
                $imageCaptcha['fonts'][array_rand($imageCaptcha['fonts'])],
                $captcha_string[$i]
            );
        }
        // tien hanh render image:
        header('Content-type: image/png');
        imagepng($imageCaptcha['image']);
        imagedestroy($imageCaptcha['image']);

        exit;
    }
}
