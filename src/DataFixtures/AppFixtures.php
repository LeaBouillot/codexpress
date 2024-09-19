<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Note;
use App\Entity\User;
use App\Entity\Network;
use App\Entity\Category;
use App\Entity\Like;
use App\Entity\Notification;
use App\Entity\Subscription;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $slug = null;
    private $hash = null;

    public function __construct(
        private SluggerInterface $slugger,
        private UserPasswordHasherInterface $hasher
    ) {
        $this->slug = $slugger;
        $this->hash = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Création de catégories
        $categories = [
            'HTML' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/html5/html5-plain.svg',
            'CSS' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/css3/css3-plain.svg',
            'JavaScript' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/javascript/javascript-plain.svg',
            'PHP' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/php/php-plain.svg',
            'SQL' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/postgresql/postgresql-plain.svg',
            'JSON' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/json/json-plain.svg',
            'Python' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/python/python-plain.svg',
            'Ruby' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/ruby/ruby-plain.svg',
            'C++' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/cplusplus/cplusplus-plain.svg',
            'Go' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/go/go-wordmark.svg',
            'bash' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/bash/bash-plain.svg',
            'Markdown' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/markdown/markdown-original.svg',
            'Java' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/java/java-original-wordmark.svg',

        ];

        $categoryArray = []; // Ce tableau nous servira pour conserver les objets Category

        foreach ($categories as $title => $icon) {
            $category = new Category(); // Nouvel objet Category
            $category
                ->setTitle($title) // Ajoute le titre
                ->setIcon($icon) // Ajoute l'icone
            ;

            array_push($categoryArray, $category); // Ajout de l'objet
            $manager->persist($category);
        }
        // ROLE_ADMIN
        $user =  new User();
        $user
            ->setEmail('hello@codexpress.fr')
            ->setUsername('lea')
            ->setPassword($this->hash->hashPassword($user, '12345321'))
            ->setRoles(['ROLE_ADMIN'])
            ->setImage('https://avatar.iran.liara.run/public/50')
        ;
        $manager->persist($user);

        $networks = ['github', 'twitter', 'linkedin', 'facebook', 'reddit', 'instagram', 'youtube'];
        // 10 utilisateurs
        for ($i = 0; $i < 10; $i++) {
            $username = $faker->userName; // Génére un username aléatoire
            $usernameFinal = $this->slug->slug($username); // Username en slug
            $user =  new User();
            $user
                ->setEmail($usernameFinal . '@' . $faker->freeEmailDomain)
                ->setUsername($username)
                ->setPassword($this->hash->hashPassword($user, 'admin'))
                ->setRoles(['ROLE_USER'])
                ->setImage('https://avatar.iran.liara.run/public/' . $i)
            ;
            // Network
            for ($z = 0; $z < 3; $z++) {
                $network = new Network();
                $network
                    ->setName($faker->randomElement($networks))
                    ->setUrl('hhtps://' . $network->getName() . '.com')
                    ->setCreator($user)
                ;
                $manager->persist($network);
            }
            $manager->persist($user);
            
            // 10 Notes
            for ($j = 0; $j < 10; $j++) {
                $note = new Note();
                $note
                    ->setTitle($faker->sentence())
                    ->setSlug($this->slug->slug($note->getTitle()))
                    ->setContent($faker->randomHtml())
                    ->setPublic($faker->boolean(50))
                    ->setViews($faker->numberBetween(100, 10000))
                    ->setCreator($user)
                    ->setCategory($faker->randomElement($categoryArray))
                    // ->addLike($faker->numberBetween(10, 1000))
                    // ->addNotification($faker->randomElement($notificationsArray))
                ;
                $manager->persist($note);
            }
            // Like
            for ($k = 0; $k < 5; $k++) {
                $like = new Like();
                $like
                    ->setCreator($user)
                    ->setNote($faker->randomElement($manager->getRepository(Note::class)->findAll()))
                ;
                $manager->persist($like);
            }
            // 10 Subscriptions
            for ($m = 0; $m < 5; $m++) {
                $subscription = new Subscription();
                $subscription
                    ->setCreator($user)
                    ->setOffer($faker->randomElement($manager->getRepository(Offer::class)->findAll()))
                ;
                $manager->persist($subscription);
            }
        }
        //10 Notifications
        for ($l = 0; $l < 10; $l++) {
            $notification = new Notification();
            $notification
                ->setTitle($faker->sentence())
                ->setContent($faker->randomHtml())
                ->setType($faker->sentence())
                ->isArchived($faker->boolean(50))
            ;
            $manager->persist($notification);
        }
        $manager->flush();
    }
}
