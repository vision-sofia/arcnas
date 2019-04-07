<?php

namespace App\Form;


use App\Entity\ConfigurationList\Condition;
use App\Entity\ConfigurationList\Element;
use App\Entity\PhotoElement;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('element', EntityType::class, [
                'class'        => Element::class,
                'choice_label' => 'name',
                'placeholder'  => '',
                'query_builder' => static function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                              ->orderBy('u.name', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {

    }
}
