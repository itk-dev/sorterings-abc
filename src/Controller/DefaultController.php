<?php

/*
 * This file is part of Sorterings-ABC.
 *
 * (c) 2018 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/app", name="app")
     */
    public function app()
    {
        return $this->render('default/app.html.twig');
    }
}
