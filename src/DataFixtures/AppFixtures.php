<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Porteur
        $porteur1 = new \App\Entity\Porteur();
        $porteur1->setDescrizione("Porteur 1");
        $porteur1->setPIN("1111");
        $porteur1->setObsoleto(false);
        $manager->persist($porteur1);

        $porteur2 = new \App\Entity\Porteur();
        $porteur2->setDescrizione("Porteur 2");
        $porteur2->setPIN("2222");
        $porteur2->setObsoleto(false);
        $manager->persist($porteur2);

        $porteur3 = new \App\Entity\Porteur();
        $porteur3->setDescrizione("Porteur 3");
        $porteur3->setPIN("3333");
        $porteur3->setObsoleto(false);
        $manager->persist($porteur3);

        // Ospitalita
        $ospitalita1 = new \App\Entity\TipologiaOspitalita();
        $ospitalita1->setDescrizione("Cashback");
        $ospitalita1->setObsoleto(false);
        $manager->persist($ospitalita1);

        $ospitalita2 = new \App\Entity\TipologiaOspitalita();
        $ospitalita2->setDescrizione("Ospitalità VIP");
        $ospitalita2->setObsoleto(false);
        $manager->persist($ospitalita2);

        // Albergo
        $albergo1 = new \App\Entity\Albergo();
        $albergo1->setDescrizione("Grand Hotel Billia");
        $albergo1->setObsoleto(false);
        $manager->persist($albergo1);

        $albergo2 = new \App\Entity\Albergo();
        $albergo2->setDescrizione("Park Hotel Billia");
        $albergo2->setObsoleto(false);
        $manager->persist($albergo2);

        $albergo3 = new \App\Entity\Albergo();
        $albergo3->setDescrizione("Albergo esterno");
        $albergo3->setObsoleto(false);
        $manager->persist($albergo3);

        // Sistemazioni
        $sistemazione1 = new \App\Entity\TipologiaSistemazione();
        $sistemazione1->setDescrizione("PARK DUS");
        $sistemazione1->setObsoleto(false);
        $manager->persist($sistemazione1);

        $sistemazione2 = new \App\Entity\TipologiaSistemazione();
        $sistemazione2->setDescrizione("PARK MATR/DP");
        $sistemazione2->setObsoleto(false);
        $manager->persist($sistemazione2);

        $sistemazione3 = new \App\Entity\TipologiaSistemazione();
        $sistemazione3->setDescrizione("GRAND DUS");
        $sistemazione3->setObsoleto(false);
        $manager->persist($sistemazione3);

        $sistemazione4 = new \App\Entity\TipologiaSistemazione();
        $sistemazione4->setDescrizione("GRAND MATR/DP");
        $sistemazione4->setObsoleto(false);
        $manager->persist($sistemazione4);

        $sistemazione5 = new \App\Entity\TipologiaSistemazione();
        $sistemazione5->setDescrizione("GRAND JUNIOR");
        $sistemazione5->setObsoleto(false);
        $manager->persist($sistemazione5);

        $sistemazione6 = new \App\Entity\TipologiaSistemazione();
        $sistemazione6->setDescrizione("PARK SUITE 2 PAX");
        $sistemazione6->setObsoleto(false);
        $manager->persist($sistemazione6);

        $sistemazione7 = new \App\Entity\TipologiaSistemazione();
        $sistemazione7->setDescrizione("Camera singola");
        $sistemazione7->setObsoleto(false);
        $manager->persist($sistemazione7);

        $sistemazione8 = new \App\Entity\TipologiaSistemazione();
        $sistemazione8->setDescrizione("Camera matrimoniale");
        $sistemazione8->setObsoleto(false);
        $manager->persist($sistemazione8);

        $sistemazione9 = new \App\Entity\TipologiaSistemazione();
        $sistemazione9->setDescrizione("Camera tripla");
        $sistemazione9->setObsoleto(false);
        $manager->persist($sistemazione9);

        // Tariffe
        $tariffa1 = new \App\Entity\Tariffa();
        $tariffa1->setDescrizione("FULL BOARD (con SPA/EXTRA)");
        $tariffa1->setObsoleto(false);
        $manager->persist($tariffa1);

        $tariffa2 = new \App\Entity\Tariffa();
        $tariffa2->setDescrizione("HALF BOARD (con SPA/EXTRA)");
        $tariffa2->setObsoleto(false);
        $manager->persist($tariffa2);

        $tariffa3 = new \App\Entity\Tariffa();
        $tariffa3->setDescrizione("BED & BREAKFAST (senza SPA/EXTRA)");
        $tariffa3->setObsoleto(false);
        $manager->persist($tariffa3);

        $manager->flush();
    }
}
