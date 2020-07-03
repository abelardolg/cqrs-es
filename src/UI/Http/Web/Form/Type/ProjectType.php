<?php

declare(strict_types=1);

namespace App\UI\Http\Web\Form\Type;


use App\UI\Http\Web\Form\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ProjectType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->createUUID($builder);
        $this->createName($builder);
        $this->createDescription($builder);
    }

    private function createUUID(FormBuilderInterface $builder): void
    {
        $builder->add('uuid', HiddenType::class);
    }

    private function createName(FormBuilderInterface $builder): void
    {
        $builder->add('projectName', TextType::class,
        [
            'required' => true
            , 'label'  => 'Introduzca el nombre del proyecto'
            , 'attr' =>
                [
                    "class" => "form-control"
                    , "placeholder" => "Nombre del proyecto"
                ]
        ]
        );
    }
    private function createDescription(FormBuilderInterface $builder): void
    {
        $builder->add('projectDescription', TextareaType::class,
            [
                'required' => true
                , 'label'  => 'Introduzca la descripción del proyecto'
                , 'attr' =>
                    [
                        "class" => "form-control"
                        , "placeholder" => "Explique brevemente en qué consiste el proyecto"
                    ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}