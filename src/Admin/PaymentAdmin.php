<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

final class PaymentAdmin extends AbstractAdmin
{
    

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('ticket.firstname');
        $datagrid->add('ticket.lastname');
        $datagrid->add('ticket.creationTime');
        $datagrid->add('isApproved');
        $datagrid->add('ticket.isValidated');

    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('ticket.firstname');
        $list->addIdentifier('ticket.lastname');
        $list->addIdentifier('ticket.sex');
        $list->addIdentifier('amount');
        $list->addIdentifier('isApproved');
        $list->addIdentifier('ticket.isValidated');
        $list->addIdentifier('ticket.creationTime');
        

    }

    
}