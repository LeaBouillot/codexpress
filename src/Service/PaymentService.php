<?php

namespace App\Service;

use App\Entity\Subscription;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PaymentService
{
    // OFFRE PREMIUM
     // NOM DE DOMAINE
     //Clé api Stripe
     // Utilisateur courant
    private $offer, $apiKey, $user, $domain;

    public function __construct(
        private ParameterBagInterface $parameter, 
        private OfferRepository $or, 
        private readonly Security $security, 
        private EntityManagerInterface $em)
    {
        $this->parameter = $parameter;
        $this->offer = $or->findOneByName('Premium'); // Récupération de l'offre Premium
        $this->apiKey = $this->parameter->get('STRIPE_API_SK');
        $this->domain = 'https://127.0.0.1:8000/en'; // NOM DE DOMAINE
        $this->user=$security->getUser();
    }

    /**
     * askCheckout()
     * Méthode permettant de créer une session de paiement Stripe
     * @return Stripe\Checkout\Session
     */
    public function askCheckout(): ?Session
    {
        Stripe::setApiKey($this->apiKey); // Établissement de la connexion (requête API)        
        $checkoutSession = Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $this->offer->getPrice() * 100, // Stripe utilise des centimes
                    'product_data' => [ // Les informations du produit sont personnalisables
                        'name' => $this->offer->getName(),
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->domain . '/payment-success',
            'cancel_url' => $this->domain . '/payment-cancel',
            'automatic_tax' => [
                'enabled' => false
            ],
        ]);
        return $checkoutSession;
    }
    // Traitement du role des utilisateurs en fonction du paiement
    public function addSubscription(): ?Subscription
    {
        $subscriptipon = new Subscription();
        $subscriptinon
        ->setCreator($this->user)
        ->setOffer($this->offer)
        ->setStartDate(new \DateTimeImmutable())
        ->setEndDate(new \DateTimeImmutable('+30 days'));
        $this->em->persist($subscriptinon);
        $this->em->flush();

        $this->user->setRole(['ROLE_PREMIUM']);
        $this->em->persist($this->user);
        $this->em->flush();

        return $subscription;
    }
        // Ajout de la souscription à l'utilisateur
        // Mise à jour des informations de paiement
        // Génération de la facture
        // Notifications email
    }

