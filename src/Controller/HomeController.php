<?php

namespace App\Controller;

use App\Form\PrenotazioneType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_prenotazione')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $elemento = null;
        $form = $this->createForm(PrenotazioneType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $elemento = $form->getData();
                       
            dd($elemento);

            $em->persist($elemento);
            $em->flush();          

            return $this->redirectToRoute('app_prenotazione');
        }
        
        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
