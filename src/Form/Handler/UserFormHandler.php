<?php

namespace App\Form\Handler;

use App\Entity\User;
use App\Utils\Manager\UserManager;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFormHandler
{

    private $userManager;
    private $passwordEncoder;

    public function __construct(UserManager $userManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userManager = $userManager;
        $this->passwordEncoder = $passwordEncoder;
    }


    public function processEditForm(Form $form) 
    {
        //$title = ucfirst(strtolower($category->getTitle()));
        $plainPassword = $form->get('plainPassword')->getData();
        $newEmail = $form->get('newEmail')->getData();

        $user = $form->getData();
        if (!$user->getId()) {
            $user->setEmail($newEmail);
        }

        if ($plainPassword) {
            $encodePassword = $this->passwordEncoder->encodePassword($user, $plainPassword);
            $user->setPassword($encodePassword);
        }
 
        $this->userManager->save($user);

        return $user;

    }


}