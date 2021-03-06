<?php

/*
 * This file is part of Sorterings-ABC.
 *
 * (c) 2018–2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/app")
 */
class AppController extends AbstractController
{
    /** @var \Symfony\Component\Asset\Packages */
    private $packages;

    /** @var \Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface */
    private $parameters;

    /** @var \Symfony\Component\HttpFoundation\RequestStack */
    private $requestStack;

    public function __construct(Packages $packages, ParameterBagInterface $parameters, RequestStack $requestStack)
    {
        $this->packages = $packages;
        $this->parameters = $parameters;
        $this->requestStack = $requestStack;
    }

    /**
     * @Route("", name="app")
     */
    public function app()
    {
        $assets = $this->getAppAssets();

        return $this->render('app/app.html.twig', [
            'assets' => $assets,
        ]);
    }

    /**
     * @Route("/iframe", name="app_iframe")
     */
    public function iframe()
    {
        $assets = $this->getAppAssets();

        return $this->render('app/iframe.html.twig', [
            'assets' => $assets,
        ]);
    }

    /**
     * @Route("/embed", name="app_embed")
     */
    public function embed()
    {
        $assets = $this->getAppAssets();

        return $this->render('app/embed.html.twig', [
            'assets' => $assets,
        ]);
    }

    /**
     * @Route("/embed/code", name="app_embed_code")
     */
    public function embedCode()
    {
        $assets = $this->getAppAssets();

        return $this->render('app/embed_code.html.twig', [
            'assets' => $assets,
        ]);
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

    /**
     * Get absolute app assets.
     */
    private function getAppAssets()
    {
        $request = $this->requestStack->getCurrentRequest();
        $assets = $this->parameters->get('app_assets');
        array_walk_recursive($assets, function (&$path, $key) use ($request) {
            $path = $request->getSchemeAndHttpHost().$this->packages->getUrl($path);
        });

        return $assets;
    }
}
