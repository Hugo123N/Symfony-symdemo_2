<?php

namespace App\Helper;

class UploadHelper
{
    public static function getProductImageFolder()
    {
        return $_ENV['BASE_UPLOAD_FOLDER'] . DIRECTORY_SEPARATOR . $_ENV['PRODUCT_UPLOAD_FOLDER'] . DIRECTORY_SEPARATOR . $_ENV['PRODUCT_IMAGE_FOLDER'];
    }
}