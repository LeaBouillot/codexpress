<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted(('AUTHENTICATION_FULLY'))]
#[Route('/profile')]
// Acces permis uniqument aux utilisateurs authentifiés 
class CreatorController extends AbstractController
{
    #[Route('/profile', name: 'app_profile', methods: ['GET'])]
    public function profile(): Response
    {
        return $this->render('creator/profile.html.twig', []);
    }

    #[Route('/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(): Response
    {
        $user = $this->getUser(); // Recupere l'utilisateur authentifié
        // TODO: Formulaire de modification et traitement des donées
        return $this->render('creator/edit.html.twig', []);
        // TODO: Formulare à envoyer à la vue Twig
    }
}
