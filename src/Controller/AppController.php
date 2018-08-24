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
use Symfony\Component\Asset\Packages;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/app")
 */
class AppController extends AbstractController
{
    /** @var \Symfony\Component\Asset\Packages */
    private $packages;

    public function __construct(Packages $packages)
    {
        $this->packages = $packages;
    }

    /**
     * @Route("", name="app")
     */
    public function app()
    {
        return $this->render('app/app.html.twig');
    }

    /**
     * @Route("/embed/code", name="app_embed_code")
     */
    public function embedCode()
    {
        return $this->render('app/embed_code.html.twig');
    }

    /**
     * @Route("/embed/style.css", name="app_embed_style")
     */
    public function embedStyle()
    {
        $url = $this->packages->getUrl('build/app.css');

        return $this->redirect($url);
    }

    /**
     * @Route("/embed/script.js", name="app_embed_script")
     */
    public function embedScript()
    {
        $url = $this->packages->getUrl('build/app.js');

        return $this->redirect($url);
    }
}