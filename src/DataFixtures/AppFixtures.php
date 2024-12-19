<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private const NB_CATEGORIES = 30;
    private const NB_ARTICLES = 200;

    public function __construct(
        private string $locale,
        private UserPasswordHasherInterface $hasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create($this->locale);
        $categories = [];

        for ($i = 0; $i < self::NB_CATEGORIES; $i++) {
            $category = new Category();
            $category->setName($faker->word());
            $manager->persist($category);
            $categories[] = $category;
        }

        for ($i = 0; $i < self::NB_ARTICLES; $i++) {
            $article = new Article();

            $article
                ->setTitle($faker->realTextBetween(10, 20))
                ->setContent($faker->text())
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 years')))
                ->setVisible($faker->boolean(80))
                ->setCategory($faker->randomElement($categories));

            $manager->persist($article);
        }

        $regularUser = new User();
        $regularUser
            ->setEmail("regular@email.com")
            ->setPassword($this->hasher->hashPassword($regularUser, "regular"));

        $manager->persist($regularUser);

        $adminUser = new User();
        $adminUser
            ->setEmail("admin@email.com")
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword($this->hasher->hashPassword($adminUser, "admin"));

        $manager->persist($adminUser);

        $manager->flush();
    }
}
