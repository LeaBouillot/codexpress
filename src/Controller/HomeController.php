<?php

namespace App\Controller;

use App\Repository\NoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(NoteRepository $nr): Response

    {
        $lastNotes = $nr->findBy(
            ['is_public' => true], //Filtre bles notes publiques
            ['created_at' => 'DESC'], // Trie les notes par date de creation
            6 // limite à 6 jours
        );
        return $this->render('home/index.html.twig', [
            'lastNotes' => $lastNotes, // Envois des notes à la vue Twig
            'totalNotes' => count($nr->findAll()) //Renvois le compte totale de notes
        ]);
    }
}