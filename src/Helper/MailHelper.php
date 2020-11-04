<?php

namespace App\Helper;

use Swift_Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Polyfill\Intl\Idn\Info;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MailHelper
{
    const SWIFT_MAILER = 1;
    const MAILER = 2;

    private $swiftMailer;
    private $mailer;

    public function __construct(
        \Swift_Mailer $swiftMailer,
        MailerInterface $mailer
    ) {
        $this->swiftMailer = $swiftMailer;
        $this->mailer = $mailer;
    }

    public function send($mailData, $type = self::SWIFT_MAILER)
    {
        switch ($type) {
            case self::SWIFT_MAILER:
                return $this->sendMailBySwiftMailer($mailData);
                break;
            case self::MAILER:
                return $this->sendMailByMailer($mailData);
                break;
            
            default:
                # code...
                break;
        }
    }

    public function sendMailBySwiftMailer($mailData)
    {
        try {
            $message = new \Swift_Message($mailData['subject']);
            $message->setFrom($mailData['from']);
            $message->setTo($mailData['to']);
            $message->setBody($mailData['body'], $mailData['body_type']);
            $this->swiftMailer->send($message);

            return [
                'status' => 'success',
                'message' => 'mail successfully sent',
                'error' => 'no error'
            ];
        } catch (\Exception $ex) {
            return [
                'status' => 'failed',
                'message' => 'mailing failed',
                'error' => [
                    get_class($ex) => $ex->getMessage(),
                ]
            ];
        }
        
    }

    public function sendMailByMailer($mailData)
    {
        try {
            $message = (new Email())
                ->from($mailData['from'])
                ->to($mailData['to'])
                ->subject($mailData['subject'])
                ->text("Hello new member:".$mailData['name'])
                ->html($mailData['body'], $mailData['body_type']);
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)

            $this->mailer->send($message);

            return [
                'status' => 'success',
                'message' => 'mail successfuly sent',
                'error' => 'no error'
            ];
        } catch (\Exception $ex) {
            return [
                'status' => 'failed',
                'message' => 'mailing failed',
                'error' => [
                    get_class($ex) => $ex->getMessage(),
                ]
            ];
        }
    }


}