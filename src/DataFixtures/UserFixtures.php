<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // User 1
        $user1 = new User();
        $user1->setEmail('mazel.florian1@gmail.com');
        $user1->setPseudo('Shugo');
        $user1->setPassword($this->passwordEncoder->hashPassword($user1, 'password'));
        $user1->setRoles(['ROLE_ADMIN']);
        $user1->setAvatar('user.png');
        $user1->setIsVerified(true);
        
        $manager->persist($user1);
        // User 2
        $user2 = new User();
        $user2->setEmail('eva@gmail.com');
        $user2->setPseudo('eva30');
        $user2->setPassword($this->passwordEncoder->hashPassword($user2, 'password'));
        $user2->setRoles(['ROLE_USER']);
        $user2->setAvatar('user.png');
        $user2->setIsVerified(true);
        
        $manager->persist($user2);


        $manager->flush();
        
    }
}
