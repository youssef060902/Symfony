<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Title')
            ->add('publicationDate', null, [
                'widget' => 'single_text',
            ])
            
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'username',
                'multiple' => false,
                'expanded' => false,
            ]);
        if ($options['include_enabled']) {
                $builder->add('enabled', CheckboxType::class, [
                    'required' => false,
                    'label' => 'Enabled',
                ]);
            }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
            'include_enabled' => false,  // Par défaut, le champ enabled n'est pas inclus
        ]);
    }
}
