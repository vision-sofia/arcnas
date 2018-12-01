<?php

namespace App\Form;


use App\Entity\ConfigurationList\Condition;
use App\Entity\ConfigurationList\Element;
use App\Entity\PhotoElement;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhotoElementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('element', EntityType::class, [
                'class'        => Element::class,
                'choice_label' => 'name',
                'placeholder'  => '',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                              ->orderBy('u.name', 'ASC');
                },
            ])
            ->add('condition', EntityType::class, [
                'class'        => Condition::class,
                'choice_label' => 'name',
                'placeholder'  => '',
            ])
            ->add('coordinates', TextType::class, [
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PhotoElement::class,
        ]);
    }
}
