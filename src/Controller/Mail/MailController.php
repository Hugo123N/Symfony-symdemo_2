<?php

namespace App\Controller\Mail;

use App\Helper\MailHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Mailer\SwiftMailer;
use App\Security\User\Account\RegisterAccountService;

class MailController extends AbstractController
{
    private $registerAccountService;

    public function __construct( RegisterAccountService $registerAccountService )
    {
        $this->registerAccountService = $registerAccountService;
    }
    /**
     * @Route("/test/mail", name="test_mail", methods={"GET"}, methods={"POST"})
     */
    public function index(Request $request, 
        MailHelper $mailHelper,
        LoggerInterface $loggerInterface
    ) {
        // lay name tu form nhap:
        $email = $request->request->get('email');
        if (empty($email)) {
            // lay name tu URL
            $email = $request->query->get('email');
        }
        $data = ['email' => $email, 'name' => ''];
        // kiem tra mail + user:
        if ($this->registerAccountService->isExistedUsernameOrEmail($data)) {
            return $this->json([ 'status' => 'failed', 'message' => 'your email or username has register' ]);
        }

        $mailData = [
            'subject' => 'Subcribe Email',
            'from' => 'Subcribe@gmail.com',
            'to' => $email,
            'body' => $this->renderView('api/mail/myMail.html.twig', [
                    'email' => $email,
                ]
            ),
            'body_type' => 'text/html'
        ];

        // chọn mailer or swift_mailer:
        $sendMailType = MailHelper::SWIFT_MAILER;
        // ==> action:
        $sendMailResult = $mailHelper->send($mailData, $sendMailType);

        if ($sendMailResult['status'] === 'success') {
            $loggerInterface->info('email sent');
        } else {
            $loggerInterface->alert('email not sent');
        }
        
        $this->addFlash('notice', 'Email sent');

        return $this->redirectToRoute('home');
    }

    
}