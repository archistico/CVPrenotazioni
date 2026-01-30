<?php

namespace App\Service;

use App\Entity\Prenotazione;
use Symfony\Component\Uid\Uuid;

class PrenotazioneSignedRequestFactory
{
    public function __construct(
        private string $agenteSecret,
        private ?string $agenteId = null,
    ) {}

    /**
     * @return array{
     *   request_id: string,
     *   payload_json: string,
     *   payload_b64: string,
     *   signature: string,
     *   meta: array<string,mixed>
     * }
     */
    public function create(Prenotazione $p): array
    {
        $requestId = Uuid::v4()->toRfc4122();

        // 1) Payload: costruiscilo come array con ordine "stabile" (importante per la firma).
        //    Non firmare direttamente un oggetto Doctrine.
        $payload = $this->buildPayloadArray($p, $requestId);

        // 2) JSON canonico: opzioni coerenti.
        $payloadJson = json_encode(
            $payload,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );

        if ($payloadJson === false) {
            throw new \RuntimeException('Impossibile serializzare payload JSON');
        }

        // 3) HMAC-SHA256 bytes -> base64
        $sigRaw = hash_hmac('sha256', $payloadJson, $this->agenteSecret, true);
        $signature = $this->base64urlEncode($sigRaw); // consigliato per trasporto

        // 4) payload in base64url (se vuoi metterlo anche nel body)
        $payloadB64 = $this->base64urlEncode($payloadJson);

        return [
            'request_id' => $requestId,
            'payload_json' => $payloadJson,
            'payload_b64' => $payloadB64,
            'signature' => $signature,
            'meta' => [
                'alg' => 'HS256', // convenzione (HMAC SHA-256)
                'agente_id' => $this->agenteId,
            ],
        ];
    }

    private function buildPayloadArray(Prenotazione $p, string $requestId): array
    {
        // Metti i campi in un ordine fisso e decidi un formato date unico.
        return [
            'request_id' => $requestId,
            'type' => 'prenotazione.v1',
            'created_at' => (new \DateTimeImmutable('now'))->format(DATE_ATOM),

            'prenotazione' => [
                'id' => $p->getId(), // ok se invii dopo flush
                'cliente' => $p->getCliente(),
                'dal' => $p->getDal()?->format('Y-m-d'),
                'al' => $p->getAl()?->format('Y-m-d'),

                'pax' => [
                    'adulti' => $p->getPaxAdulti(),
                    'bambini' => $p->getPaxBambini(),
                    'adolescenti' => $p->getPaxAdolescenti(),
                ],

                // relazioni: tienile “piatte” (id + descrizione) per semplicità
                'albergo' => [
                    'id' => $p->getFkAlbergo()?->getId(),
                    'descrizione' => $p->getFkAlbergo()?->getDescrizione(),
                ],
                'tipologia_sistemazione' => [
                    'id' => $p->getFkTipologiaSistemazione()?->getId(),
                    'descrizione' => $p->getFkTipologiaSistemazione()?->getDescrizione(),
                ],
                'tipologia_ospitalita' => [
                    'id' => $p->getFkTipologiaOspitalita()?->getId(),
                    'descrizione' => $p->getFkTipologiaOspitalita()?->getDescrizione(),
                ],
                'tariffa' => [
                    'id' => $p->getFkTariffa()?->getId(),
                    'descrizione' => $p->getFkTariffa()?->getDescrizione(),
                ],
                'porteur' => [
                    'id' => $p->getFkPorteur()?->getId(),
                    'descrizione' => $p->getFkPorteur()?->getDescrizione(),
                ],

                'note' => $p->getNote(),
            ],
        ];
    }

    private function base64urlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function verify(string $payloadJson, string $signature): bool
    {
        $sigRaw = hash_hmac('sha256', $payloadJson, $this->agenteSecret, true);
        $expected = $this->base64urlEncode($sigRaw);
    
        return hash_equals($expected, $signature);
    }
}
