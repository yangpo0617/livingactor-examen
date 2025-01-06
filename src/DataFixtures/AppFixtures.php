<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Entity\Enum\UserRoleEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $employeeA = new Employee();
        $employeeA->setName('John Doe')
            ->setSalary(1000.48)
            ->setEmail('john.doe@test.com');
        $employeeB = new Employee();
        $employeeB->setName('Mary Smith')
            ->setSalary(5000.19)
            ->setEmail('mary.smith@test.com');

        $manager->persist($employeeA);
        $manager->persist($employeeB);
        $manager->flush();
    }
}
