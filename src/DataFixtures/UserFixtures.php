<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $date = new DateTime();

        // Profil du maire, unique obligatoire
        $mayor = new User();
        $mayor->setEmail('samierfabien@gmail.com');
        $mayor->setRoles(['ROLE_MAYOR']);
        $mayor->setPassword($this->passwordHasher->hashPassword($mayor, 'azer'));
        $mayor->setCreatedAt($date);
        $mayor->setLastModification($date);
        $mayor->setFirstName('Fabien');
        $mayor->setLastName('Samier');
        $mayor->setNewsletter(true);
        $mayor->setVote(true);
        $mayor->setEvent(true);
        $mayor->setSurvey(true);
        $mayor->setRgpd(true);
        $mayor->setUserTermsOfUse(true);
        $mayor->setEmployeeTermsOfUse(true);

        $manager->persist($mayor);

        // enregistre l'article dans une référence pour pouvoir la réutiliser dans les autres fixtures
        $this->addReference('mayor', $mayor);

        // Ajout d'autres utilisateurs accessoires
        $users = [
            1 => [
                'email' => 'samier.fabien84@gmail.com',
                'roles' => ['ROLE_AGENT'],
                'password' => 'azer',
                'first_name' => 'Fabien2',
                'last_name' => 'Samier2',
                'newsletter' => true,
                'vote' => true,
                'event' => true,
                'survey' => true,
                'rgpd' => true,
                'user_terms_of_use' => true,
                'employee_terms_of_use' => true,
            ],
            2 => [
                'email' => 'madebymagash@gmail.com',
                'roles' => ['ROLE_USER'],
                'password' => 'azer',
                'first_name' => '',
                'last_name' => '',
                'newsletter' => true,
                'vote' => true,
                'event' => true,
                'survey' => true,
                'rgpd' => true,
                'user_terms_of_use' => true,
                'employee_terms_of_use' => false,
            ]
        ];

        // Création des entités en fonction des $users
        foreach ($users as $key => $value) {
            $date = new DateTime();
            $user = new User();
            $user->setEmail($value['email']);
            $user->setRoles($value['roles']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $value['password']));
            $user->setCreatedAt($date);
            $user->setLastModification($date);
            $user->setFirstName($value['first_name']);
            $user->setLastName($value['last_name']);
            $user->setNewsletter($value['newsletter']);
            $user->setVote($value['vote']);
            $user->setEvent($value['event']);
            $user->setSurvey($value['survey']);
            $user->setRgpd($value['rgpd']);
            $user->setUserTermsOfUse($value['user_terms_of_use']);
            $user->setEmployeeTermsOfUse($value['employee_terms_of_use']);

            $manager->persist($user);

            $this->addReference('user_' . $key, $user);
        }

        $manager->flush();
    }
}
