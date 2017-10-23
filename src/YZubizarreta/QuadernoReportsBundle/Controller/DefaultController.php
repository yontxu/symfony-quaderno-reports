<?php

namespace YZubizarreta\QuadernoReportsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('YZQuadernoReportsBundle:Default:index.html.twig');
    }
}
