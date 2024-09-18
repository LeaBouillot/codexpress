<?php

namespace App\Controller;

use App\Repository\NoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function search(Request $request, NoteRepository $nr): Response
    {
        dd(
            $nr->findByQuery(
                $request->get('q')
            ));

        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }
}
