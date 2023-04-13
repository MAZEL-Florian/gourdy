<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $slugger = new AsciiSlugger();

        $categories = [
            [
                'nomCategorie' => 'Cuisine',
                'image' => 'cuisine.jpg',
            ],
            [
                'nomCategorie' => 'Littérature',
                'image' => 'litterature.jpg',
            ],
            [
                'nomCategorie' => 'Voyage',
                'image' => 'voyage.jpg',
            ],
            [
                'nomCategorie' => 'Informatique',
                'image' => 'informatique.jpg',
            ],
            [
                'nomCategorie' => 'Musique',
                'image' => 'musique.jpg',
            ],
            [
                'nomCategorie' => 'Cinéma',
                'image' => 'cinema.jpg',
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = new Categorie();
            $category->setNomCategorie($categoryData['nomCategorie']);

            $filename = $slugger->slug($categoryData['nomCategorie']) . '.' . pathinfo($categoryData['image'], PATHINFO_EXTENSION);
            copy(__DIR__ . '/../../public/images/' . $categoryData['image'], __DIR__ . '/../../public/images/' . $filename);
            $category->setImage($filename);

            $manager->persist($category);
        }

        $manager->flush();
    }

    public function getDependencies(){
        return [
            SujetFixtures::class
        ];
    }
}
