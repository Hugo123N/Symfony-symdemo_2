<?php

namespace App\DTO\Request\Account;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class CreateAccountRequest
{
    /**
     * @Assert\NotBlank(
     *      message="Email is required."
     * )
     * @Assert\Email(
     *      message="Email is invalid."
     * )
     */
    public $email;

    /**
     * @Assert\NotBlank(
     *      message="Username is required."
     * )
     */
    public $userName;

    /**
     * @Assert\NotBlank(
     *      message="Password is required."
     * )
     */
    public $password;

    /**
     * @Assert\NotBlank(
     *      message="re-Password is required."
     * )
     * @Assert\IdenticalTo(
     *      propertyPath="password",
     *      message="re_pass must be the same as Password"
     * )
     */
    public $rePassword;

    public function __construct()
    {
        return true;
    }

    public function buildByRequest(Request $request)
    {
        $this->email = $request->request->get('email');
        $this->userName = $request->request->get('name');
        $this->password = $request->request->get('password');
        $this->rePassword = $request->request->get('re_password');
    }

    public function convertToEntity()
    {
    }
}
