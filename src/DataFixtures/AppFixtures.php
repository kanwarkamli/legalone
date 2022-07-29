<?php

namespace App\DataFixtures;

use App\Entity\LogFactory;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Status Code = 201
        // Service Name = USER-SERVICE
        for ($i = 0; $i < 5; $i++) {
            $data = LogFactory::create(201, 'USER-SERVICE', Carbon::today()->subDays(rand(0, 7)));
            $manager->persist($data);
        }

        // Status Code = 201
        // Service Name = INVOICE-SERVICE
        for ($i = 0; $i < 20; $i++) {
            $data = LogFactory::create(201, 'INVOICE-SERVICE', Carbon::today()->subDays(rand(0, 7)));
            $manager->persist($data);
        }

        // Status Code = 400
        // Service Name = USER-SERVICE
        for ($i = 0; $i < 6; $i++) {
            $data = LogFactory::create(400, 'USER-SERVICE', Carbon::today()->subDays(rand(0, 7)));
            $manager->persist($data);
        }

        // Status Code = 400
        // Service Name = INVOICE-SERVICE
        for ($i = 0; $i < 7; $i++) {
            $data = LogFactory::create(400, 'INVOICE-SERVICE', Carbon::today()->subDays(rand(0, 7)));
            $manager->persist($data);
        }

        $manager->flush();
    }
}
