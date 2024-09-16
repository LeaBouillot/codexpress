<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/notes')] //sufixe pour les route controller

class NoteController extends AbstractController
{
    #[Route('/', name: 'app_note_all', methods: ['GET'])]
    public function all(NoteRepository $nr): Response
    {
        $notes = $nr->findAll(
            ['is_public' => true], //Filtre bles notes publiques
            ['created_at' => 'DESC']
        );
        return $this->render('note/all.html.twig', [
            'allNotes' => $notes,
        ]);
    }

    #[Route('/u/{slug}', name: 'app_note_show', methods: ['GET'])]
    public function show(string $slug, NoteRepository $nr): Response
    {
        $note = $nr->findOneBySlug(['slug' => $slug]);
        return $this->render('note/show.html.twig', [
            'note' => $note,
        ]);
    }

    #[Route('/u/{username}', name: 'app_note_user', methods: ['GET'])]
    public function userNotes(
        string $username,
        UserRepository $user, // Cette fois on utilise le repository User
    ): Response {
        $creator = $user->findByUsername($username); // Recherche de l'utilisateur
        return $this->render('note/user.html.twig', [
            'creator' => $creator, // Envoie les  données de l'utilsateur à la vue Twig
            'userNote' => $creator->getNotes(), // Récupère les notes de l'utilisateur
        ]);
    }

    #[Route('/new', name: 'app_note_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $note = new Note(); // Création d'une nouvelle note
        $form = $this->createForm(NoteType::class, $note); // Chargement du formulaire
        $form = $form->handleRequest($request); //recuperation des données de la request POST

        dd($form->getData()); // Dump and die pour voir les données

        //Traitement des données
        if($form->isSubmitted() && $form->isValid()) {
            // tu enregistres la note en bdd
        }
        return $this->render('note/new.html.twig', [
            'noteForm' => $form
        ]);
    }

    #[Route('/edit{slug}', name: 'app_note_edit', methods: ['GET', 'POST'])]
    public function edit(string $slug, NoteRepository $nr): Response
    {
        $note = $nr->findOneBySlug($slug); // recuperer la note via le
        // TODO: Formulaire de modification et traitement des donées
        return $this->render('note/edit.html.twig', [
            'note' => $note, // Envoie les données de la note à la vue Twig
        ]);
    }

    #[Route('/delete{slug}', name: 'app_note_delete', methods: ['POST'])]
    public function delete(string $slug, NoteRepository $nr): Response
    {
        $note = $nr->findOneBySlug($slug); // Recherche de la note à supprimer
        // TODO: Traitement de suppression
        $this->addFlash('success', 'Your code snippet has been deleted.');
        return $this->redirectToRoute('app_note_user'); // Redirection vers la page
    }
}
