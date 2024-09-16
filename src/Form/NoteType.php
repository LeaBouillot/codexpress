<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Note;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'row_attr' => ['class' => 'flex flex-col gap_1'],
                'label' => 'Choose a title',
                'label_attr' => ['class' => 'text-violet-950 front-semibold'],
                'attr' => ['class' => 'border-violet-950 rounded-md p-2 w-full focus:border-violet-600'],
                'help' => 'This id title or your note',
                'help_attr' => ['class' => 'text-sm text-violet-600']
            ])
            ->add('content', TextareaType::class, [
                'row_attr' => ['class' => 'flex flex-col gap_1'],
                'label' => 'Write your code',
                'label_attr' => ['class' => 'text-violet-950 front-semibold'],
                'attr' => ['class' => 'border-violet-950 rounded-md p-2 w-full focus:border-violet-600'],
                'help' => 'What do you want to share on Codexpress?',
                'help_attr' => ['class' => 'text-sm text-violet-600']
            ])
            ->add('is_public')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'id',
            ])
            ->add('creator', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('submit', SubmitType::class)
            // à SUPPRIMER: ce qu on n'a pas besoin
            // ->add('slug')
            //->add('views')
            //  ->add('created_at', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('updated_at', null, [
            //     'widget' => 'single_text',
            // ])

            // ->add('title', TextType::class, [ ]
            // (nom property, type, [tableau associatif option ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
