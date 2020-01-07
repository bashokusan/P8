<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $users =[];

        $user = new User();
        $user->setUsername('user');
        $user->setPassword($this->encoder->encodePassword($user, 'password'));
        $user->setEmail('user@user.com');
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);
        $users[] = $user;

        $user2 = new User();
        $user2->setUsername('admin');
        $user2->setPassword($this->encoder->encodePassword($user, 'password'));
        $user2->setEmail('admin@admin.com');
        $user2->setRoles(['ROLE_ADMIN']);
        $manager->persist($user2);
        $users[] = $user2;

        for ($p = 1; $p < 5; $p++){
            $task = new Task();
            $task->setTitle('task n-'.mt_rand());
            $task->setContent("fake content of the task");
            $task->setAuthor($users[array_rand($users)]);
            $manager->persist($task);
        }

        $manager->flush();
    }
}
