<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class NewsletterController extends AbstractController
{
    /**
     * @Route("/newsletter", name="newsletter")
     */
    public function index(): Response
    {
        return $this->render('newsletter/index.html.twig', [
            'controller_name' => 'NewsletterController',
        ]);
    }

    /**
     * @IsGranted("ROLE_AGENT")
     * @Route("/newsletter/agent/creation", name="newsletterCreation")
     */
    public function create(): Response
    {
        return $this->render('newsletter/creation.html.twig', [
            'controller_name' => 'NewsletterController',
        ]);
    }
}
