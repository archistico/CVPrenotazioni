<?php

namespace App\Controller\Api;

use App\Entity\Porteur;
use App\Entity\Prenotazione;
use App\Security\PinHasher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class PrenotazioneApiController extends AbstractController
{
    #[Route('/prenotazioni', name: 'prenotazioni_list', methods: ['GET'])]
    public function list(EntityManagerInterface $em, Request $request): JsonResponse
    {
        $apiKey = $request->headers->get('X-API-KEY');
        if ($apiKey !== $_ENV['API_KEY_CONSUMER']) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $limit = max(1, min(200, (int) $request->query->get('limit', 50)));
        $page  = max(1, (int) $request->query->get('page', 1));
        $offset = ($page - 1) * $limit;

        $repo = $em->getRepository(Prenotazione::class);

        // Se hai campi tipo createdAt/updatedAt puoi ordinare meglio.
        // Qui uso id desc come default.
        $items = $repo->findBy([], ['id' => 'DESC'], $limit, $offset);

        $data = array_map([$this, 'mapPrenotazione'], $items);

        return $this->json([
            'page' => $page,
            'limit' => $limit,
            'count' => count($data),
            'items' => $data,
        ]);
    }

    #[Route('/prenotazioni/{id}', name: 'prenotazioni_get', methods: ['GET'])]
    public function getOne(EntityManagerInterface $em, Request $request, int $id): JsonResponse
    {
        $apiKey = $request->headers->get('X-API-KEY');
        if ($apiKey !== $_ENV['API_KEY_CONSUMER']) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $p = $em->getRepository(Prenotazione::class)->find($id);
        if (!$p) {
            return $this->json(['error' => 'Not found'], 404);
        }

        return $this->json($this->mapPrenotazione($p));
    }

    #[Route('/porteurs', name: 'porteurs_create', methods: ['POST'])]
    public function createPorteur(EntityManagerInterface $em, Request $request, PinHasher $pinHasher): JsonResponse
    {
        $apiKey = $request->headers->get('X-API-KEY');
        if ($apiKey !== $_ENV['API_KEY_ADMIN']) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $payload = json_decode($request->getContent(), true);
        if (!is_array($payload)) {
            return $this->json(['error' => 'Invalid JSON body'], 400);
        }

        $descrizione = trim((string) ($payload['descrizione'] ?? ''));
        $pin = (string) ($payload['pin'] ?? '');
        $obsoleto = (bool) ($payload['obsoleto'] ?? false);

        if ($descrizione === '' || $pin === '') {
            return $this->json(['error' => 'Fields "descrizione" and "pin" are required'], 400);
        }

        $porteur = new Porteur();
        $porteur->setDescrizione($descrizione);
        $porteur->setPIN($pinHasher->hash($pin));
        $porteur->setObsoleto($obsoleto);

        $em->persist($porteur);
        $em->flush();

        return $this->json([
            'id' => $porteur->getId(),
            'descrizione' => $porteur->getDescrizione(),
            'obsoleto' => $porteur->isObsoleto(),
        ], 201);
    }

    #[Route('/porteurs', name: 'porteurs_list', methods: ['GET'])]
    public function listPorteurs(EntityManagerInterface $em, Request $request): JsonResponse
    {
        $apiKey = $request->headers->get('X-API-KEY');
        if ($apiKey !== $_ENV['API_KEY_ADMIN']) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $items = $em->getRepository(Porteur::class)->findBy([], ['id' => 'ASC']);
        $data = array_map(static fn (Porteur $p): array => [
            'id' => $p->getId(),
            'descrizione' => $p->getDescrizione(),
            'obsoleto' => $p->isObsoleto(),
        ], $items);

        return $this->json([
            'count' => count($data),
            'items' => $data,
        ]);
    }

    #[Route('/porteurs/{id}', name: 'porteurs_update', methods: ['PUT', 'PATCH'])]
    public function updatePorteur(EntityManagerInterface $em, Request $request, PinHasher $pinHasher, int $id): JsonResponse
    {
        $apiKey = $request->headers->get('X-API-KEY');
        if ($apiKey !== $_ENV['API_KEY_ADMIN']) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $payload = json_decode($request->getContent(), true);
        if (!is_array($payload)) {
            return $this->json(['error' => 'Invalid JSON body'], 400);
        }

        /** @var Porteur|null $porteur */
        $porteur = $em->getRepository(Porteur::class)->find($id);
        if (!$porteur) {
            return $this->json(['error' => 'Not found'], 404);
        }

        if (array_key_exists('descrizione', $payload)) {
            $descrizione = trim((string) $payload['descrizione']);
            if ($descrizione === '') {
                return $this->json(['error' => 'Field "descrizione" cannot be empty'], 400);
            }
            $porteur->setDescrizione($descrizione);
        }

        if (array_key_exists('pin', $payload)) {
            $pin = (string) $payload['pin'];
            if ($pin === '') {
                return $this->json(['error' => 'Field "pin" cannot be empty'], 400);
            }
            $porteur->setPIN($pinHasher->hash($pin));
        }

        if (array_key_exists('obsoleto', $payload)) {
            $porteur->setObsoleto((bool) $payload['obsoleto']);
        }

        $em->flush();

        return $this->json([
            'id' => $porteur->getId(),
            'descrizione' => $porteur->getDescrizione(),
            'obsoleto' => $porteur->isObsoleto(),
        ]);
    }

    private function mapPrenotazione(Prenotazione $p): array
    {
        return [
            'id' => $p->getId(),
            'cliente' => $p->getCliente(),
            'dal' => $p->getDal()?->format('Y-m-d'),
            'al' => $p->getAl()?->format('Y-m-d'),
            'pax' => [
                'adulti' => $p->getPaxAdulti(),
                'bambini' => $p->getPaxBambini(),
                'adolescenti' => $p->getPaxAdolescenti(),
            ],
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
        ];
    }
}
