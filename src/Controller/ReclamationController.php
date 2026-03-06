<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReclamationController extends AbstractController
{
    // ─── Afficher toutes les réclamations (TD section 10 → index) ────
    #[Route('/reclamations', name: 'app_reclamation_index')]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        $reclamations = $reclamationRepository->findAllOrderedByDate();

        // Statistiques pour les cards du dashboard
        $total     = count($reclamations);
        $enAttente = count(array_filter($reclamations, fn($r) => $r->getStatut() === 'En attente'));
        $traitees  = count(array_filter($reclamations, fn($r) => $r->getStatut() === 'Traitée'));

        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
            'total'        => $total,
            'en_attente'   => $enAttente,
            'traitees'     => $traitees,
        ]);
    }

    // ─── Ajouter une nouvelle réclamation (TD section 10 → new) ──────
    #[Route('/reclamation/nouvelle', name: 'app_reclamation_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $nom     = $request->request->get('nom');
            $email   = $request->request->get('email');
            $sujet   = $request->request->get('sujet');
            $message = $request->request->get('message');

            if ($nom && $email && $sujet && $message) {
                $reclamation = new Reclamation(); // constructeur → statut + date auto
                $reclamation->setNom($nom);
                $reclamation->setEmail($email);
                $reclamation->setSujet($sujet);
                $reclamation->setMessage($message);

                $entityManager->persist($reclamation);
                $entityManager->flush();

                $this->addFlash('success', 'Réclamation déposée avec succès !');
                return $this->redirectToRoute('app_reclamation_index');
            }

            $this->addFlash('error', 'Tous les champs sont obligatoires.');
        }

        return $this->render('reclamation/ajouter.html.twig');
    }

    // ─── Voir le détail d'une réclamation (TD section 10 → show) ─────
    #[Route('/reclamation/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/consulter.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    // ─── Modifier une réclamation (TD section 10 → edit) ─────────────
    #[Route('/reclamation/{id}/modifier', name: 'app_reclamation_edit')]
    public function edit(
        Request $request,
        Reclamation $reclamation,
        EntityManagerInterface $entityManager
    ): Response {
        if ($request->isMethod('POST')) {
            $nom     = $request->request->get('nom');
            $email   = $request->request->get('email');
            $sujet   = $request->request->get('sujet');
            $message = $request->request->get('message');
            $statut  = $request->request->get('statut');

            if ($nom && $email && $sujet && $message) {
                $reclamation->setNom($nom);
                $reclamation->setEmail($email);
                $reclamation->setSujet($sujet);
                $reclamation->setMessage($message);
                $reclamation->setStatut($statut);

                $entityManager->flush();

                $this->addFlash('success', 'Réclamation modifiée avec succès !');
                return $this->redirectToRoute('app_reclamation_index');
            }

            $this->addFlash('error', 'Tous les champs sont obligatoires.');
        }

        return $this->render('reclamation/modifier.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    // ─── Supprimer une réclamation (TD section 10 → delete) ──────────
    // POST uniquement + vérification CSRF (comme dans le TD)
    #[Route('/reclamation/{id}/supprimer', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Reclamation $reclamation,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
            $this->addFlash('success', 'Réclamation supprimée avec succès !');
        }

        return $this->redirectToRoute('app_reclamation_index');
    }

    // ─── Basculer le statut En attente ↔ Traitée (POST) ──────────────
    #[Route('/reclamation/{id}/statut', name: 'app_reclamation_statut', methods: ['POST'])]
    public function toggleStatut(
        Reclamation $reclamation,
        EntityManagerInterface $entityManager
    ): Response {
        $reclamation->setStatut(
            $reclamation->getStatut() === 'En attente' ? 'Traitée' : 'En attente'
        );
        $entityManager->flush();

        $this->addFlash('success', 'Statut mis à jour : ' . $reclamation->getStatut());
        return $this->redirectToRoute('app_reclamation_show', ['id' => $reclamation->getId()]);
    }
}
