<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends AbstractType
{

    private function getConfiguration($label, $placeholder, $options = [])
    {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                $this->getConfiguration("Titre", "Tapez votre titre ici")
            )
            ->add(
                'slug',
                TextType::class,
                $this->getConfiguration("Url", "adresse web automatique", [
                    'required' => false
                ]),

            )
            ->add(
                'coverImage',
                UrlType::class,
                $this->getConfiguration("Url de l'image principale", "Donnez l'adresse de l'image")
            )
            ->add(
                'introduction',
                TextType::class,
                $this->getConfiguration("Introduction", "Donnez une description globale de l'annonce")
            )
            ->add(
                'content',
                TextareaType::class,
                $this->getConfiguration('Description détailléé', 'Tapez une description ici')
            )
            ->add(
                'rooms',
                IntegerType::class,
                $this->getConfiguration("Nombre de chambres", "Le nombre de chambre disponibles")
            )
            ->add(
                'price',
                MoneyType::class,
                $this->getConfiguration('Prix par nuit', "Indiquez le prix que vous voulez pour nuit")
            )
            ->add(
                'images',
                CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
