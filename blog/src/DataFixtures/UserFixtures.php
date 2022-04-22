<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserFixtures extends Fixture
{
    private $encoder;
    private $slugger;

    public function __construct(UserPasswordHasherInterface $encoder, SluggerInterface $slugger){
        $this->encoder = $encoder;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@admin.fr');
        $admin->setName('Grosse Bertha');
        $admin->setSlug($this->slugger->slug($admin->getName() . '-'.rand(100, 999))->lower());
        $admin->setAvatar('default.jpg');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->encoder->hashPassword($admin,'azerty'));

        $manager->persist($admin);


        $user = new User();
        $user->setEmail('user@user.fr');
        $user->setName('Antoine');
        $user->setSlug($this->slugger->slug($user->getName() . '-'.rand(100, 999))->lower());
        $user->setAvatar('default.jpg');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->encoder->hashPassword($user,'azerty'));

        $manager->persist($user);
        $manager->flush();
    }
}
