<?php

namespace App\Validation;

// duong dan
use App\Helper\UploadHelper;

// ho tro uploadflie
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ValidatedUpload
{
    // kiem tra tinh hop le file upload create:
    public static function validateImageCreate($imageFile)
    {
        // lay width + height cua file ảnh:
        list($width, $height) = getimagesize($imageFile);
        // lay đuôi file:
        $typeFile = $imageFile->getClientMimeType();
        // lay size file:
        $sizeFile = $imageFile->getClientSize();

        if (
            !empty($imageFile) && ($sizeFile < 1000000) &&
            (($sizeFile == "image/gif") || ($typeFile == "image/jpeg") || ($typeFile == "image/jpg") || ($typeFile == "image/png")) &&
            ($width < 400) && ($height < 500)
        ) {
            return true;
        }
        return false;
    }

    // kiem tra tinh hop le file upload edit:
    public static function validateImageEdit($imageFile)
    {
        // lay width + height cua file ảnh:
        list($width, $height) = getimagesize($imageFile);
        // lay đuôi file:
        $typeFile = $imageFile->getClientMimeType();
        // lay size file:
        $sizeFile = $imageFile->getClientSize();
        if (
            (($sizeFile == "image/gif") || ($typeFile == "image/jpeg") || ($typeFile == "image/jpg") || ($typeFile == "image/png"))
            && ($sizeFile < 1000000)
            && ($width < 400) && ($height < 500)
        ) {
            return true;
        }
        return false;
    }

    // thuc hien upload:
    public static function uploadImage(UploadedFile $imageFile)
    {
        $dir = UploadHelper::getProductImageFolder();
        $fileName = uniqid() . '-' . $imageFile->getClientOriginalName();

        // Xu ly upload file image
        $imageFile->move($dir, $fileName);

        return $fileName;
    }
}
