<?php

namespace App\Controller\Api;

use App\Entity\Prenotazione;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
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
