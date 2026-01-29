<?php

namespace App\Service;

use App\Entity\Prenotazione;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class PrenotazioneMailer
{
    public function __construct(
        private MailerInterface $mailer,
        private string $fromEmail,
        private string $toEmail
    ) {}

    public function inviaPrenotazione(Prenotazione $p): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->fromEmail, 'Prenotazioni'))
            ->to($this->toEmail)
            ->subject(sprintf('Prenotazione: %s (%s - %s)',
                $p->getCliente(),
                $p->getDal()?->format('d/m/Y'),
                $p->getAl()?->format('d/m/Y')
            ))
            ->htmlTemplate('email/prenotazione.html.twig')
            ->context([
                'p' => $p,
            ]);

        $this->mailer->send($email);
    }
}
