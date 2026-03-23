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

        // Sécurité : seul un client peut lister les produits
        if (!$user || $this->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->redirectToRoute('app_accueil');
        }

        $produits = $prodRepo->findAll();

        // Traitement du formulaire POST
        if ($request->isMethod('POST')) {
            $idProduit = $request->request->get('id_produit');
            $nouvelleQt = (int)$request->request->get('quantite');
            $produit = $prodRepo->find($idProduit);

            if ($produit) {
                // Récupérer l'ancienne quantité dans le panier pour ce produit
                $lignePanier = $panierRepo->findOneBy(['user' => $user, 'product' => $produit]);
                $ancienneQt = $lignePanier ? $lignePanier->getQuantite() : 0;

                // Calcul de la variation pour ajuster le stock
                $difference = $nouvelleQt - $ancienneQt;
                $produit->setQuantiteStock($produit->getQuantiteStock() - $difference);

                // Gestion de la ligne de panier
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

            // Après validation, on reste sur la même page
            return $this->redirectToRoute('app_produits');
        }

        return $this->render('product/index.html.twig', [
            'produits' => $produits,
        ]);
    }
}
