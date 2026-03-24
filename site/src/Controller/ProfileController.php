<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($request->isMethod('POST')) {
            $oldPassword = $request->request->get('old_password');
            $newPassword = $request->request->get('new_password');

            // 1. Vérification obligatoire de l'ancien mot de passe
            if (!$hasher->isPasswordValid($user, $oldPassword)) {
                $this->addFlash('danger', 'Ancien mot de passe incorrect.');
                return $this->redirectToRoute('app_profile');
            }

            // 2. Mise à jour des informations de base
            $user->setNom($request->request->get('nom'));
            $user->setPrenom($request->request->get('prenom'));

            // 3. Gestion du nouveau mot de passe (si rempli)
            if (!empty($newPassword)) {
                // Règle de gestion : Interdiction de mettre le login comme mot de passe
                if ($newPassword === $user->getLogin()) {
                    $this->addFlash('danger', 'Le mot de passe ne peut pas être votre login.');
                    return $this->redirectToRoute('app_profile');
                }

                // Hachage du nouveau mot de passe
                $hashedPassword = $hasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);
            }

            $em->flush();
            $this->addFlash('success', 'Profil mis à jour !');

            // Redirection selon le rôle
            return $this->isGranted('ROLE_SUPER_ADMIN')
                ? $this->redirectToRoute('app_accueil')
                : $this->redirectToRoute('app_produits');
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }
}
