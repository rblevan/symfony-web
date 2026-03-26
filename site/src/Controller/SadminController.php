<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[IsGranted('ROLE_SUPER_ADMIN')]
class SadminController extends AbstractController
{
    #[Route('/sadmin/manage-admins', name: 'app_sadmin_manage_admins')]
    public function index(UserRepository $userRepository): Response
    {
        $allUsers = $userRepository->findAll();
        $admins = [];

        // On ne garde que ceux qui ont le ROLE_ADMIN mais PAS le ROLE_SUPER_ADMIN
        foreach ($allUsers as $user) {
            if (in_array('ROLE_ADMIN', $user->getRoles()) && !in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
                $admins[] = $user;
            }
        }

        return $this->render('sadmin/manage_admins.html.twig', [
            'admins' => $admins,
        ]);
    }
    #[Route('/sadmin/add-admin', name: 'app_sadmin_add_admin')]
    public function addAdmin(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em): Response
    {
        $admin = new User();
        $form = $this->createForm(RegistrationFormType::class, $admin); // On réutilise le formulaire d'inscription
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ON FORCE LE RÔLE ADMIN ICI
            $admin->setRoles(['ROLE_ADMIN']);

            $admin->setPassword($hasher->hashPassword($admin, $form->get('plainPassword')->getData()));
            $em->persist($admin);
            $em->flush();

            $this->addFlash('success', 'Nouvel administrateur créé !');
            return $this->redirectToRoute('app_sadmin_manage_admins');
        }

        return $this->render('sadmin/add_admin.html.twig', ['registrationForm' => $form->createView()]);
    }

    #[Route('/sadmin/demote-admin/{id}', name: 'app_sadmin_demote_admin')]
    public function demote(User $admin, EntityManagerInterface $em): Response
    {
        // On remplace ses rôles par le rôle de base
        $admin->setRoles(['ROLE_USER']);
        $em->flush();

        $this->addFlash('success', "L'utilisateur {$admin->getLogin()} n'est plus administrateur.");
        return $this->redirectToRoute('app_sadmin_manage_admins');
    }
}
