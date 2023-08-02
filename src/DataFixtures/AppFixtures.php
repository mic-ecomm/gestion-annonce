<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr-FR');
        
        for ($i = 1; $i <= 30; $i++) {

            $title = $faker->sentence();
            $randomNumber = mt_rand(1, 55000);
            $coverImage = "https://picsum.photos/800/600?random=" . $randomNumber;

            $introduction = $faker->paragraph(2);
            $paragraphs = $faker->paragraphs(5);

            $content = '<p>' . implode('</p><p>', $paragraphs) . '</p>';

            $ad = new Ad;
            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5));

            for ($j = 0; $j < mt_rand(2, 5); $j++) { 
                $randomNumber = mt_rand(1, 55000);
                $imageUrl = "https://picsum.photos/800/600?random=" . $randomNumber;

                $image = new Image();
                $image->setUrl($imageUrl) 
                    ->setCaption($faker->sentence())
                    ->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
        }

        $manager->flush();
    }
}
