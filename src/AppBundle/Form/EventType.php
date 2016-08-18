<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType,
    Symfony\Component\Form\Extension\Core\Type\TextareaType,
    Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['required' => true])
            ->add('shortDescription', TextAreaType::class, ['required' => true])
            ->add('descriptionBlock1', TextAreaType::class, ['required' => true])
            ->add('descriptionBlock2', TextareaType::class, ['required' => true])
            ->add('dateFrom', DateTimeType::class, ['date_widget' => 'single_text', 'required' => true])
            ->add('dateTo', DateTimeType::class, ['date_widget' => 'single_text'])
            ->add('location', TextType::class)
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'            => 'AppBundle\Entity\Event',
            'csrf_protection'       => false,
            'allow_extra_fields'    => true
        ]);
    }
}
