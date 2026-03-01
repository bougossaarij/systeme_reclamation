<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReclamationController extends AbstractController
{
    #[Route('/reclamation', name: 'app_reclamation')]
    public function index(): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'controller_name' => 'ReclamationController',]);
        // Ajouter
        return $this->render('reclamation/ajouter.html.twig', [
        // variables à passer au Twig
        ]);

        // Modifier
        return $this->render('reclamation/modifier.html.twig', [
        // variables à passer au Twig
        ]);

        // Consulter
        return $this->render('reclamation/consulter.html.twig', [
        // variables à passer au Twig
        ]);

        // Afficher
        return $this->render('reclamation/afficher.html.twig', [
        // variables à passer au Twig
        ]);

    }
}
