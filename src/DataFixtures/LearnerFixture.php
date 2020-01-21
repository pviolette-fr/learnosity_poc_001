<?php

namespace App\DataFixtures;

use App\Entity\Learner;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LearnerFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $learner = new Learner();
        $learner->setName("Smith");
        $learner->setFirstname("John");
        $manager->persist($learner);

        $manager->flush();
    }
}
