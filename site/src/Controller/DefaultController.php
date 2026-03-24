<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        $user = $this->getUser();
        $statut = 'visiteur anonyme';
        $pays = null;

        if ($user) {
            // Détermination du statut selon les rôles
            if ($this->isGranted('ROLE_SUPER_ADMIN')) {
                $statut = 'super-administrateur';
            } elseif ($this->isGranted('ROLE_ADMIN')) {
                $statut = 'administrateur';
            } else {
                $statut = 'client';
            }

            // Récupération du pays si renseigné
            if ($user->getPays()) {
                $pays = $user->getPays()->getNom();
            }
        }

        return $this->render('accueil/index.html.twig', [
            'statut' => $statut,
            'pays' => $pays,
        ]);
    }
}
