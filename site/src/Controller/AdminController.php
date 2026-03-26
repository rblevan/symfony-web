<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'app_admin_')]
final class AdminController extends AbstractController
{
    // pour ajouter un produit
    #[Route('/produit/ajouter', name: 'produit_add')]
    public function addProduct(Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('danger', 'Accès refusé. Vous devez être administrateur.');
            return $this->redirectToRoute('app_accueil');
        }

        $produit = new Product();
        $form = $this->createForm(ProductType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($produit);
            $em->flush();

            $this->addFlash('success', 'Le produit a bien été ajouté au catalogue !');

            return $this->redirectToRoute('app_produits');
        }
        return $this->render('admin/add_product.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    // pour les clients
    #[Route('/clients', name: 'clients')]
    public function listClients(UserRepository $userRepo): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_accueil');
        }
        $users = $userRepo->findAll();

        return $this->render('admin/clients.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/client/delete/{id}', name: 'client_delete', methods: ['POST'])]
    public function deleteClient(User $client, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_accueil');
        }
        if ($client === $this->getUser()) {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer votre propre compte.');
            return $this->redirectToRoute('app_admin_clients');
        }
        if (in_array('ROLE_SUPER_ADMIN', $client->getRoles())) {
            $this->addFlash('danger', 'Impossible de supprimer un Super-Administrateur.');
            return $this->redirectToRoute('app_admin_clients');
        }
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $em->remove($client);
            $em->flush();
            $this->addFlash('success', 'Le compte de '.$client->getPrenom().' a bien été supprimé.');
        }

        return $this->redirectToRoute('app_admin_clients');
    }
}
