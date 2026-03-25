<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Repository\ProductRepository;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_produits')]
    public function list(
        ProductRepository $prodRepo,
        PanierRepository $panierRepo,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->redirectToRoute('app_accueil');
        }

        if ($request->isMethod('POST')) {

            // visiteur non connecté ajout panier -> login
            if (!$user) {
                $this->addFlash('error', 'Vous devez être connecté pour ajouter des articles au panier.');
                return $this->redirectToRoute('app_login');
            }

            $idProduit = $request->request->get('id_produit');
            $nouvelleQt = (int)$request->request->get('quantite');
            $produit = $prodRepo->find($idProduit);

            if ($produit) {
                $lignePanier = $panierRepo->findOneBy(['user' => $user, 'product' => $produit]);
                $ancienneQt = $lignePanier ? $lignePanier->getQuantite() : 0;

                $difference = $nouvelleQt - $ancienneQt;
                $produit->setQuantiteStock($produit->getQuantiteStock() - $difference);

                if ($nouvelleQt <= 0) {
                    if ($lignePanier) {
                        $em->remove($lignePanier);
                    }
                } else {
                    if (!$lignePanier) {
                        $lignePanier = new Panier();
                        $lignePanier->setUser($user);
                        $lignePanier->setProduct($produit);
                    }
                    $lignePanier->setQuantite($nouvelleQt);
                    $em->persist($lignePanier);
                }
                $em->flush();
                $this->addFlash('success', 'Panier mis à jour.');
            }
            return $this->redirectToRoute('app_produits');
        }

        // visiteur / client
        $produits = $prodRepo->findAll();

        return $this->render('product/add_product.html.twig', [
            'produits' => $produits,
        ]);
    }
}
