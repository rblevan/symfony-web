<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(): Response
    {
        // Vérification de sécurité : seul un client accède au panier
        if (!$this->getUser() || $this->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('panier/add_product.html.twig');
    }

    #[Route('/panier/vider', name: 'panier_vider')]
    public function vider(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        // On rend les produits au stock avant de vider
        foreach ($user->getPaniers() as $ligne) {
            $produit = $ligne->getProduct();
            $produit->setQuantiteStock($produit->getQuantiteStock() + $ligne->getQuantite());
            $em->remove($ligne);
        }
        $em->flush();

        $this->addFlash('success', 'Le panier a été vidé et les stocks mis à jour.');
        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/commander', name: 'panier_commander')]
    public function commander(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        // On supprime le panier sans rendre le stock (expédition)
        foreach ($user->getPaniers() as $ligne) {
            $em->remove($ligne);
        }
        $em->flush();

        $this->addFlash('success', 'Commande validée !');
        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/supprimer/{id}', name: 'panier_supprimer')]
    public function supprimer(Product $product, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        // On cherche la ligne spécifique dans le panier
        foreach ($user->getPaniers() as $ligne) {
            if ($ligne->getProduct() === $product) {
                // Rendre le stock pour ce produit précis
                $product->setQuantiteStock($product->getQuantiteStock() + $ligne->getQuantite());
                $em->remove($ligne);
                break;
            }
        }
        $em->flush();
        return $this->redirectToRoute('app_panier');
    }
}
