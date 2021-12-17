<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GestionController extends AbstractController
{
    /**
     * @Route("/{locale}/gestion", name="gestion")
     */
    public function index(): Response
    {
        return $this->render('gestion/index.html.twig', [
            
        ]);
    }
}
