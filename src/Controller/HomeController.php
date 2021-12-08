<?php

namespace App\Controller;

use App\Repository\NewsletterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $newsletterRepo;

    public function __construct(NewsletterRepository $newsletterRepo)
    {
        $this->newsletterRepo = $newsletterRepo;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $newsletters = $this->newsletterRepo->findLast(4);

        return $this->render('home/index.html.twig', [
            'newsletters' => $newsletters,
        ]);
    }
}
