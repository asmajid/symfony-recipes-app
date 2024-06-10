<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Webmozart\Assert\Assert as AssertAssert;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez le nom de l\'ingrédient...',
                    'minlength' => 5,
                    'maxlength' => 50
                ],
                'label' => 'Nom',
                'constraints' => [
                    new Length(['min' => 2, 'max' => 50]),
                    new NotBlank()
                ]
            ])
            ->add('price', MoneyType::class, [
                'attr' => ['placeholder' => 'Prix de l\'ingrédient...'],
                'label' => 'Prix',
                'constraints' => [
                    new Positive(),
                    new LessThan(200)
                ]
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}
