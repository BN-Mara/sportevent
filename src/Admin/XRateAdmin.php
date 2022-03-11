<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

final class XRateAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('usd', NumberType::class,[
            'label'=>"USD",
            'data'=>1,
            'attr'=>[
                'readonly'=>true
            ]

        ]);
        $form->add('cdf', NumberType::class,[
            'label'=>"CDF for 1 USD",
            'required'=>true
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('usd');
        $datagrid->add('cdf');

    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('usd');
        $list->addIdentifier('cdf');
       
    }

    
}