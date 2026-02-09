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

    public function inviaPrenotazioneFirmata(Prenotazione $p, array $signed): void
    {
        $filename = sprintf('prenotazione_%s.json', $signed['request_id']);
    
        $email = (new TemplatedEmail())
            ->from(new Address($this->fromEmail, 'Prenotazioni'))
            ->to($this->toEmail)
            ->subject(sprintf(
                'Richiesta, %s, %s - %s',
                $p->getCliente(),
                $p->getDal()->format('d/m/Y'),
                $p->getAl()->format('d/m/Y')
            ))
            ->htmlTemplate('email/prenotazione_firmata.html.twig')
            ->context([
                'p' => $p,
                'request_id' => $signed['request_id'],
                'signature' => $signed['signature'],
                'payload_b64' => $signed['payload_b64'], // opzionale mostrarlo
                'meta' => $signed['meta'],
            ])
            // Allegato JSON “vero”
            ->attach($signed['payload_json'], $filename, 'application/json');
    
        $this->mailer->send($email);
    }

}
