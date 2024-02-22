<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

use Symfony\Component\Mime\Email;

class MailerService
{
    // This service allow us to send mails:
    //    1- After user registration
    //    2- Succesfull payment
    //    3- Contact page form
    //     
    private MailerInterface $mailer;

    public function __construct(MailerInterface $sender)
    {
        $this->mailer = $sender;
    }

    function getMailer() {
        return $this->mailer;        
    }

    // sending email with text and html
    public function emailSend(string $email)
    {
        $mail = (new Email())
            ->from('bmaconsultingnet@gmail.com')
            ->to($email)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Order Confirmation')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $this->mailer->send($mail);
    }

    // sending mail with twig templating
    public function twigEmailSend(int $type, string $email, string $fullname, array $items)
    {
        switch ($type) {
            case 1 : // after registration
                $subject = "Thanks for your registration!";
                $template = "emails/registration.html.twig";
                break;
            case 2: // after payment
                $subject = "Thanks for buying our products!";
                $template = "emails/confirmation.html.twig";
                break;
            case 3: // after payment
                $subject = "Thanks for your feedback";
                $template = "emails/contact.html.twig";
                break;
            default:
                break;
        }
        $mail = (new TemplatedEmail())
            ->from('bmaconsultingnet@gmail.com')
            ->to($email)
            ->subject($subject)

            // path of the Twig template to render
            ->htmlTemplate($template)

            // pass variables (name => value) to the template
            ->context([
                //     'expiration_date' => new \DateTime('+7 days'),
                'name' => $fullname,
                'items' => $items
            ]);

            $this->getMailer()->send($mail);
    }
}
