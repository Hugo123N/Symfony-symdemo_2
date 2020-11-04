<?php

namespace App\Controller\Security\User;

use App\DTO\Request\Account\CreateAccountRequest;
use App\Helper\MailHelper;
use App\Validation\ValidatedRequest;

use App\Security\User\Account\RegisterAccountService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterController extends AbstractController
{
    private $registerAccountService;
    private $router;

    public function __construct(RegisterAccountService $registerAccountService, RouterInterface $router){
        $this->registerAccountService = $registerAccountService;
        $this->router = $router;
    }
    /**
     * @Route("/register", name="app_register_user")
     */
    public function registerIndex()
    {
        return $this->render('security/user/register.html.twig');
    }


    /**
     * @Route("account/createAction", name="createAccount")
     */
    public function createAccount(Request $request,
        MailHelper $mailHelper,
        LoggerInterface $loggerInterface,
        CreateAccountRequest $createAccountRequest,
        ValidatorInterface $validator
    ) {
        $data = $request->request->all();

        // validate thuan bang PHP:
        // if (!ValidatedRequest::isValidatedName($data) ||
        //     !ValidatedRequest::isValidatedEmail($data) ||
        //     !ValidatedRequest::isValidatedPass($data)) {
        //     return $this->json([
        //         'notificate' => [ 'status' => 'failed', 'message' => $errors ]
        //     ]);
        // }

        // validate bang doctrine symfony:
        $createAccountRequest->buildByRequest($request);
        $errors  = $validator->validate($createAccountRequest);
        // lay tat ca loi trong validate:
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            return $this->json([
                'notificate' => [ 'status' => 'failed', 'messages' => $messages ]
            ]);
        }
        
        // kiem tra mail + user:
        if ($this->registerAccountService->isExistedUsernameOrEmail($data)) {
            return $this->json([
                'notificate' => [ 'status' => 'failed', 'message' => 'your email or username has register' ]
            ]);
        }

        // phan quyen:
        $data['roles'] = ['ROLE_USER'];

        // tien hành tao account -> database:
        $registerResult = $this->registerAccountService->createAccount($data);

        // tien hanh gui mail thong bao:
        $mailData = [
            'subject' => 'Register new user Account',
            'from' => 'MyWebsite@site.com',
            'to' => $data['email'],
            'body' => $this->renderView('api/mail/myMail.html.twig', [
                'name' => $data['name'],
                'email' => $data['email']
            ]),
            'body_type' => 'text/html',
            'name' => $data['name']
        ];
        // chọn phuong thuc mail:
        // $sendMailType = MailHelper::SWIFT_MAILER;
        $sendMailType = MailHelper::MAILER;
        // tien hanh gui mail
        $sendMailResult = $mailHelper->send($mailData, $sendMailType);
        // tao logger quan li gui mail
        if ( $sendMailResult['status'] === 'success' ) {
            $loggerInterface->info('email sent');
        } else {
            $loggerInterface->alert('email not sent');
        }

        // ===> tra ve kq:
        if ($registerResult['status'] === 'success' && $sendMailResult['status'] === 'success') {
            $href = $this->router->generate('app_login_user');
            return $this->json([
                'notificate' => [ 'status' => 'success',
                'href' => $href,
                'subject' => 'successful registration!',
                'message' => 'please log in to your account to continue..'
                ]
            ]);
        } else {
            return $this->json([
                'notificate' => [
                    'status' => 'failed',
                    'registerResult' => $registerResult, 'sendMailResult' => $sendMailResult ]
            ]);
        }
    }


}