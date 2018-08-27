<?php

/*
 * This file is part of Sorterings-ABC.
 *
 * (c) 2018 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace App\Controller;

use AlterPHP\EasyAdminExtensionBundle\Controller\AdminController as BaseAdminController;
use App\Entity\User;

class AdminController extends BaseAdminController
{
    public function createNewUserEntity()
    {
        $user = $this->get('fos_user.user_manager')->createUser();
        $user->setEnabled(true);

        return $user;
    }

    public function persistUserEntity(User $user)
    {
        $user->setUsername($user->getEmail());
        $this->get('fos_user.user_manager')->updateUser($user, false);
        parent::persistEntity($user);
    }

    public function updateUserEntity(User $user)
    {
        $user->setUsername($user->getEmail());
        $this->get('fos_user.user_manager')->updateUser($user, false);
        parent::updateEntity($user);
    }
}
