<?php

namespace App\Twig\Components;

use App\Entity\Like;
use App\Entity\Note;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class LikeButton
{
    public Note $note;
    public ?User $currentUser; // 

    public function __construct(private Security $security) // Security pour recuperer currentUser
    {
        $this->currentUser = $this->security->getUser();
    }

    public function getLikesCount(): int
    {
        return $this->note->getLikes()->count(); // recupere like et les compte
    }

    public function hasUserLiked(): bool
    {
        if (!$this->currentUser) {
            return false;
        }
        // $this (likeButton), note(proprity-objet), getLikes(tableau objet), exists(méthode, currentUser == creator like )
        return $this->note->getLikes()->exists(function($key, Like $like) {
            return $like->getCreator() === $this->currentUser;
            // User qui possede le like === User current
        });
    }
}