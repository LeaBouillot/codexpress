<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

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

    #[Route('/n/{slug}', name: 'app_note_show', methods: ['GET'])]
    public function show(string $slug, NoteRepository $nr): Response
    {
        $note = $nr->findOneBySlug(['slug' => $slug]); // Objet Note
        // $creatorNotes= array_slice($note->getCreator()->getNotes(), 0, 3);

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
    // #[IsGranted('IS_AUTHENTICATED_FULLY')] //redirection à la page 
    #[Route('/new', name: 'app_note_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        if (!$this->getUser()) { //si utilisateur n'est pas conecté
            $this->addFlash('error', 'You must be logged in to create a note.');
            return $this->redirectToRoute('app_login'); // redirection à la page login si l'utilisateur n'est pas connecté.
        }

        $form = $this->createForm(NoteType::class); // Chargement du formulaire
        $form = $form->handleRequest($request); // Recuperation des données de la requête POST

        // Traitement des données
        if ($form->isSubmitted() && $form->isValid()) {
            $note = new Note();
            $note
                ->setTitle($form->get('title')->getData())
                ->setSlug($slugger->slug($note->getTitle()))
                ->setContent($form->get('content')->getData())
                ->setPublic($form->get('is_public')->getData())
                ->setCategory($form->get('category')->getData())
                ->setCreator($form->get('creator')->getData())
            ;
            $em->persist($note);
            $em->flush();

            $this->addFlash('success', 'Your note has been created');
            return $this->redirectToRoute('app_note_show', ['slug' => $note->getSlug()]);
        }
        return $this->render('note/new.html.twig', [
            'noteForm' => $form
        ]);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/edit/{slug}', name: 'app_note_edit', methods: ['GET', 'POST'])]
    public function edit(
        string $slug, 
        NoteRepository $nr,
        Request $request,
        EntityManagerInterface $em,
        ): Response
    {
        $note = $nr->findOneBySlug($slug); // recuperer la note à modifier

        if ($note->getCreator() !== $this->getUser()){ 
            $this->addFlash('error', 'You authorized to edit a note.');
        return $this->redirectToRoute('app_note_show', ['slug' =>$slug]);
    }

        $form = $this->createForm(NoteType::class, $note); // Chargement du formulaire avaec des données de la note
        $form = $form->handleRequest($request); // Recuperation des données de la requête POST
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($note);
            $em->flush();

            $this->addFlash('success', 'Your note has been created');
            return $this->redirectToRoute('app_note_show', ['slug' => $note->getSlug()]);
        }
    
        // TODO: Formulaire de modification et traitement des donées
        return $this->render('note/edit.html.twig', ['noteForm' => $form]);
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
