<?php

namespace App\Controller;

use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Security\Http\LoginLink\LoginLinkNotification;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/{locale}/connexion", name="app_login")
     */
    public function login(string $locale = 'fr', AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr');
            return $this->redirect("/");
        }

        if ($this->getUser()) {
            $this->addFlash('notice', 'Vous êtes déja connecté, si vous voulez vous connecter avec un autre compte, déconnectez vous d\'abord.');
            return $this->redirectToRoute('home', [
                'locale' => $locale,
            ]);
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $userEmail = ($this->getUser()) ? $this->getUser()->getEmail() : null;


        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'userEmail' => $userEmail,
        ]);
    }

    /**
     * @Route("/{locale}/deconnexion", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/{locale}/oubli", name="app_forgot")
     */
    public function linkCreation(string $locale, NotifierInterface $notifier, LoginLinkHandlerInterface $loginLinkHandler, UserRepository $userRepo, Request $request, MailerInterface $mailer)
    {
        // Vérification que la locales est bien dans la liste des langues sinon retour accueil en langue française
        if (!in_array($locale, $this->getParameter('app.locales'), true)) {
            $request->getSession()->set('_locale', 'fr'); 
            return $this->redirect("/");
        }

        // Vérifie si le formulaire de connexion est soumis
        if ($request->isMethod('POST') && $this->isCsrfTokenValid('generateLink', $request->request->get('_token'))) {
            // Charge l'utilisateur à partir du champs 'email'
            $email = $request->request->get('email');
            $user = $userRepo->findOneBy(['email' => $email]);
            if (!is_null($user)) {
                // Crée un lien magique pour l'utilisateur, cela retourne une instance de 
                $loginLinkDetails = $loginLinkHandler->createLoginLink($user);

                // Envoi du lien par e-mail
                $subject = ($locale === "fr") ? "Récupération": "Recovery";
                $text = ($locale === "fr") ?
                "Bonjour. Vous trouverez ci-dessous le lien de récupération que vous avez demandé. Si vous n'êtes pas à l'origine de cette demande ou que vous avez fait une fausse manipulation, n'en tenez pas compte. " . $loginLinkDetails->getUrl() :
                "Hello. Below is the recovery link you asked for. If you are not the one that initiated that request or you made a bad movement, ignore it. " . $loginLinkDetails->getUrl();
                $email = (new Email())
                    ->from('samierfabien@gmail.com')
                    ->to($user->getEmail())
                    //->cc('cc@example.com')
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    //->priority(Email::PRIORITY_HIGH)
                    ->subject($subject)
                    ->text($text);
                    //->html('<p>See Twig integration for better HTML integration!</p>');
        
                try {
                    $mailer->send($email);
                } catch (TransportExceptionInterface $e) {
                    $this->addFlash('danger', 'Une erreur est survenue, veuillez recommencer l\'opération s\'il vous plait.');
                }

                $this->addFlash('notice', 'Si l\'adresse donnée est valide, un e-mail va vous parvenir à cette adresse.');
            }
        }

        return $this->render('security/forgot.html.twig');
    }
}
