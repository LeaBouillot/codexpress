<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailNotificationService
{
    public function __construct(private MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(string $receiver): ?string
    {
        try {
            $email = (new Email())
                ->from('hello@codexpress.fr')
                ->to($receiver)
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Time for Symfony Mailer!')
                ->text('Sending emails is fun again!')
                ->html('<p>See Twig integration for better HTML integration!</p>');

            $this->mailer->send($email);
            return 'The e-mail was sucessfully sent!';
        } catch (\Exception $e) {
            return 'An error occurred while sending the e-mail: ' . $e->getMessage();
        }
    }
}



// if ($case === 'premium') {
//     $email
//         ->subject('Thank you for your purchase')
//         ->priority(Email::PRIORITY_HIGH)
//         ->htmlTemplate('email/base.html.twig');
// } elseif ($case === 'registeration') {
//     $email
//         ->subject('Welcome to Codexpress')
//         ->htmlTemplate('email/welcome.html.twig');;
// }

// $this->mailer->send($email);
// return 'Email sent successfully!';
// } catch (\Exception $e) { {
//     return 'An error occurred while sending the email: ' . $e->getMessage();
// }
// }
