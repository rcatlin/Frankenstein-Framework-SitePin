<?php

namespace SitePin\Controller;

use SitePin\Component\Controller;

class MainController extends Controller
{
    public function index()
    {
        return $this->renderResponse(
            'home.html.twig',
            array(
                'currPage' => 'homepage',
            )
        );
    }

    public function about()
    {
        return $this->renderResponse(
            'about.html.twig',
            array(
                'currPage' => 'about',
            )
        );
    }
}
