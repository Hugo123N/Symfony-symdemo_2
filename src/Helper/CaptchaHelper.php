<?php
namespace App\Helper;

use Symfony\Component\HttpKernel\KernelInterface;

class CaptchaHelper
{
    protected $projectDir;

    public function __construct(KernelInterface $kernel)
    {
        $this->projectDir = $kernel->getProjectDir();
    }

    public function createImageCaptcha()
    {
        $image = imagecreatetruecolor(100, 38);
 
        imageantialias($image, true);
        
        
        // background color:
        $colors = [];
        $red = rand(125, 175);
        $green = rand(125, 175);
        $blue = rand(125, 175);
        
        for ($i = 0; $i < 5; $i++) {
            $colors[] = imagecolorallocate($image, $red - 20*$i, $green - 20*$i, $blue - 20*$i);
        }
        imagefill($image, 0, 0, $colors[0]);
        
        // line color:
        for ($i = 0; $i < 5; $i++) {
            imagesetthickness($image, rand(2, 10));
            $line_color = $colors[rand(1, 4)];
            imagerectangle($image, rand(-10, 190), rand(-10, 10), rand(-10, 190), rand(40, 60), $line_color);
        }
        // font color:
        // $black = imagecolorallocate($image, 0, 0, 0);
        // $white = imagecolorallocate($image, 255, 255, 255);
        $white = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
        $black = imagecolorallocate($image, 0x00, 0x00, 0x00);
        $textcolors = [$black, $white];
        // choose font type:
        $fonts = [
            $this->projectDir.'\public\assetsAPI\fonts\captcha\SyneMono-Regular.ttf',
            $this->projectDir.'\public\assetsAPI\fonts\captcha\Grandstander-VariableFont_wght.ttf'
        ];

        return [
            'image' => $image,
            'textcolors' => $textcolors,
            'fonts' => $fonts
        ];
    }



    public function generateString($input, $strength = 10)
    {
        $input_length = strlen($input);
        $random_string = '';
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }
}