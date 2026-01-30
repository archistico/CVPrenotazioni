<?php

namespace App\Controller;

use App\Form\PrenotazioneType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\PrenotazioneMailer;
use App\Service\PrenotazioneSignedRequestFactory;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_prenotazione')]
    public function index(Request $request, EntityManagerInterface $em, PrenotazioneSignedRequestFactory $factory, PrenotazioneMailer $mailer): Response
    {
        $elemento = null;
        $form = $this->createForm(PrenotazioneType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            /** @var \App\Entity\Prenotazione $elemento */
            $elemento = $form->getData();
            $elemento->setCosto(0);
            
            $em->persist($elemento);
            $em->flush();          

            // invio mail (sincrono, semplice)
            //$prenotazioneMailer->inviaPrenotazione($elemento);

            $signed = $factory->create($elemento);
            if (!$factory->verify($signed['payload_json'], $signed['signature'])) {
                throw new \RuntimeException('Firma non valida (test).');
            }

            $mailer->inviaPrenotazioneFirmata($elemento, $signed);

            $this->addFlash('success', 'Prenotazione salvata e inviata via email.');

            return $this->redirectToRoute('app_prenotazione');
        }
        
        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
