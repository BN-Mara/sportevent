<?php
// src/Admin/UserAdmin.php

namespace App\Admin;

use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserAdmin extends AbstractAdmin
{
    private $hasher;
    public function __construct($code, $class, $baseControllerName, UserPasswordHasherInterface $hasher)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->hasher = $hasher;;
        
    }
    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('names', TextType::class);
        $form->add('email', EmailType::class);
        $form->add('roles', ChoiceType::class, [
            'required' => true,
            'multiple' => false,
            'expanded' => false,
            'choices'  => [
              'User' => 'ROLE_USER',
              'Partner' => 'ROLE_PARTNER',
              'Admin' => 'ROLE_ADMIN',
              'Super admin'=>'ROLE_SUPER_ADMIN',
              
            ],
        ]);
        $form->add('password',PasswordType::class);
        $form->get('roles')
        ->addModelTransformer(new CallbackTransformer(
            function ($rolesArray) {
                 // transform the array to a string
                 return count(array($rolesArray))? $rolesArray[0]: null;
            },
            function ($rolesString) {
                 // transform the string back to an array
                 return [$rolesString];
            }
    ));

    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('names');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('names');
        $list->addIdentifier('email');
        $list->addIdentifier('roles');
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist(object $object): void
    {
        
        $plainPassword = $object->getPassword();  
        $object->setPassword($this->hasher->hashPassword($object, $plainPassword));
    }

     /**
     * {@inheritdoc}
     */
    public function preUpdate(object $object): void
    {
        
        $plainPassword = $object->getPassword();  
        $object->setPassword($this->hasher->hashPassword($object, $plainPassword));
    }


   
}
