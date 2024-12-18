<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;

class AppFixtures extends Fixture
{
    private const NB_CATEGORIES = 30;
    private const NB_ARTICLES = 200;

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create("zh_TW");
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
        $manager->flush();
    }
}