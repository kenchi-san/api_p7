<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\ProductPhone;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $product = [
            "listPhone"=>["sumsung","iphone"],
            "version"=>[1,2,3,4,5,6,7,8,9,10]
        ];



        $faker = Faker\Factory::create('fr_FR');

        $customer = new Customer();
        $customer->setName($faker->firstName);
        $customer->setSurname($faker->lastName);
        $customer->setAddress($faker->address);
        $customer->setMail($faker->email);
        $customer->setPhone($faker->phoneNumber);
        $customer->setMembershipNumber($faker->swiftBicNumber);


        $user = new User();
        $user->setName($faker->firstName);
        $user->setSurname($faker->lastName);
        $user->setEmail($faker->email);
        $user->setPassword("bibi");
        $user->setPositionInTheCompagny($faker->jobTitle);

        $phone = new ProductPhone();
        $phone->setName("samsung");
        $phone->setDescription($faker->paragraph($nbSentences = 3, $variableNbSentences = true));
        $phone->setPrice(random_int(200,1500));

        $manager->persist($user);
        $manager->persist($customer);
        $manager->persist($phone);

        $manager->flush();
    }
}
