<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\ProductPhone;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class AppFixtures extends Fixture
{
    const CUSTOMER_FAKE_NUMBER = 10;
    const PRODUCT_FAKE_NUMBER = 10;//max 10
    const USER_FAKE_NUMBER = 3;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->faker = Faker\Factory::create('fr_FR');
    }

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
        $u = 0;
        while ($u <= self::USER_FAKE_NUMBER) {

            $user = new User();
            $user->setName("user".$u);
            $user->setSurname("surname".$u);
            $user->setEmail("user".$u."@gmail.com");
            $user->setPassword($this->userPasswordHasher->hashPassword($user, "bibi"));
            $user->setPositionInTheCompagny($this->faker->jobTitle);

            $this->createCustomers($user);

            $manager->persist($user);
            $u++;

        }


        $p = 0;
        while ($p <= self::PRODUCT_FAKE_NUMBER) {

            $phone = new ProductPhone();
            $phone->setName($AllPhones[array_rand($AllPhones,1)]);
            $phone->setDescription($this->faker->paragraph($nbSentences = 3, $variableNbSentences = true));
            $phone->setPrice(random_int(200, 1500));
            $manager->persist($phone);
            $p++;

        }
        $manager->flush();
    }

    private function createCustomers(User $user)
    {

        $c = 0;
        while ($c <= self::CUSTOMER_FAKE_NUMBER) {
            $customer = new Customer();
            $customer->setName($this->faker->firstName);
            $customer->setSurname($this->faker->lastName);
            $customer->setAddress($this->faker->address);
            $customer->setMail($this->faker->email);
            $customer->setPhone(125698534);
            $customer->setMembershipNumber($this->faker->swiftBicNumber);

            $user->addCustomer($customer);
            $c++;
        }
    }
}
