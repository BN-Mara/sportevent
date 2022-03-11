<?php

namespace App\Admin;

//use App\Entity\Prime;

use App\Entity\SportEvent;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

final class PriceAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('sportEvent', EntityType::class, [
            'required' => true,
            'class' => SportEvent::class,
            'choice_label' => 'title',
        ]);
        $form->add('price', NumberType::class,[
            'label'=>"Price (USD)",
        ]);
        $form->add('type', ChoiceType::class, [
            'required' => true,
            'multiple' => false,
            'expanded' => false,
            'choices'  => [
              'ECONOMIC' => 'ECONOMIC',
              'BUSINESS' => 'BUSINESS'
            ],
        ]);
        
        $form->add('isEnabled', ChoiceType::class,[
            'required' => true,
            'multiple' => false,
            'expanded' => false,
            'choices'  => [
              'ENABLED' => true,
              'DISABLED' => false,
            ],
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('price');
        $datagrid->add('type');
        $datagrid->add('isEnabled');
        $datagrid->add('sportEvent.title');

    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('price');
        $list->addIdentifier('type');
        $list->addIdentifier('isEnabled');
        $list->addIdentifier('sportEvent.title');
    }

    
}