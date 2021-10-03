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
    const CUSTOMER_FAKE_NUMBER = 10;
    const PRODUCT_FAKE_NUMBER = 10;//max 10
    const USER_FAKE_NUMBER = 3;
    public function load(ObjectManager $manager)
    {

        $product = [
            "listPhone" => ["sumsung", "iphone"],
            "version" => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
        ];

        $AllPhones=[];
        foreach ($product["listPhone"] as $key=>$value){
            $AllPhones[]=$value;
        }
        $faker = Faker\Factory::create('fr_FR');
        $c = 0;
        while ($c <= self::CUSTOMER_FAKE_NUMBER) {

            $customer = new Customer();
            $customer->setName($faker->firstName);
            $customer->setSurname($faker->lastName);
            $customer->setAddress($faker->address);
            $customer->setMail($faker->email);
            $customer->setPhone(125698534);
            $customer->setMembershipNumber($faker->swiftBicNumber);
            $manager->persist($customer);
            $c++;
        }
        $u = 0;
        while ($u <= self::USER_FAKE_NUMBER) {

            $user = new User();
            $user->setName($faker->firstName);
            $user->setSurname($faker->lastName);
            $user->setEmail($faker->email);
            $user->setPassword("bibi");
            $user->setPositionInTheCompagny($faker->jobTitle);
            $manager->persist($user);
            $u++;

        }

        $p = 0;
        while ($p <= self::PRODUCT_FAKE_NUMBER) {

            $phone = new ProductPhone();
            $phone->setName($AllPhones[array_rand($AllPhones,1)]);
            $phone->setDescription($faker->paragraph($nbSentences = 3, $variableNbSentences = true));
            $phone->setPrice(random_int(200, 1500));
            $manager->persist($phone);
            $p++;

        }
        $manager->flush();
    }
}
