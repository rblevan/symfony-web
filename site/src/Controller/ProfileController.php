<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PaysRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher, PaysRepository $paysRepo): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            $oldPassword = $request->request->get('old_password');
            $newPassword = $request->request->get('new_password');

            if (!$hasher->isPasswordValid($user, $oldPassword)) {
                $this->addFlash('danger', 'Ancien mot de passe incorrect.');
                return $this->redirectToRoute('app_profile');
            }

            $user->setNom($request->request->get('nom'));
            $user->setPrenom($request->request->get('prenom'));

            $idNouveauPays = $request->request->get('pays');
            if ($idNouveauPays) {
                $nouveauPays = $paysRepo->find($idNouveauPays);
                if ($nouveauPays) {
                    $user->setPays($nouveauPays);
                }
            }

            if (!empty($newPassword)) {
                if ($newPassword === $user->getLogin()) {
                    $this->addFlash('danger', 'Le mot de passe ne peut pas être votre login.');
                    return $this->redirectToRoute('app_profile');
                }
                $hashedPassword = $hasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);
            }

            $em->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès !');

            return $this->isGranted('ROLE_SUPER_ADMIN')
                ? $this->redirectToRoute('app_accueil')
                : $this->redirectToRoute('app_produits');
        }

        $tousLesPays = $paysRepo->findAll();

        return $this->render('profile/add_product.html.twig', [
            'user' => $user,
            'les_pays' => $tousLesPays,
        ]);
    }
}
