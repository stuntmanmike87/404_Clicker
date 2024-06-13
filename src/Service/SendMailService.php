<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

final readonly class SendMailService // SendEmailService
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    /** @param  array<string> $context */
    public function send(
        string $from,
        string $to,
        string $subject,
        string $template,
        array $context
    ): void {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate(sprintf('emails/%s.html.twig', $template))
            ->context($context);

        $this->mailer->send($email);
    }
}
