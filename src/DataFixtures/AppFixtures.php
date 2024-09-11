<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Note;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class AppFixtures extends Fixture

{
    private $slug = null;
    private $hash = null;

    public function __construct(

        private SluggerInterface $slugger, //enlever espaces et en minisucule
        private UserPasswordHasherInterface $hasher    // hasher mdp
    ) {
        $this->slug = $slugger;
        $this->hash = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        # Tableau contenant les catégories
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

        $categoryArray = []; // ce tablea nopus servira pour conserver les objets Category
        foreach ($categories as $title => $icon) {
            $category = new Category(); //Nouvel objet Category
            $category
                ->setTitle($title) //  ajoute le titre
                ->setIcon($icon); // ajoute l icon

            array_push($categoryArray, $category); // ajouter de l'objet
            $manager->persist($category);
        }
        $manager->flush();
        // 10 utilisateurs
        for ($i = 0; $i < 10; $i++) {
            $username = $faker->userName;
            $usernameFinal = $this->slug->slug($username); //username en slug
            $user = new User();
            $user
                ->setEmail($usernameFinal . $faker->freeEmailDomain) //generer email
                ->setUsername($username)
                ->setPassword(
                    $this->hash->hashPassword($user, 'admin') //2 parametres(user, mdp)
                )
                ->setRoles(['ROLE_USER']);

            $manager->persist($user);
            // 10 notes
            for ($j = 0; $j < 10; $j++) {
                $note = new Note();
                $note
                    ->setTitle($faker->sentence())
                    ->setSlug($this->slug->slug($note->getTitle()))
                    ->setContent($faker->paragraphs(4, true))
                    ->setPublic($faker->boolean(50))
                    ->setViews($faker->numberBetween(100, 10000))
                    ->setCreator($user)
                    ->setCategory($faker->randomElement($categoryArray))
                ;
                $manager->persist($note);
            }
        }
        $manager->flush();
    }
}
