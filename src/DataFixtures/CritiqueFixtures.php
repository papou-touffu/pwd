<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Critique;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class CritiqueFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = \Faker\Factory::create('fr_FR');
        // Créer 3 catégories fakés
        for($i =1 ;$i <=3;$i++) {
            $category = new Category();
            $category->setTitle($faker->sentence())
                ->setDescription($faker->paragraph());
            $manager->persist($category);

            // creer des critiques entre 3 et 5 par catégorie
            for ($j = 1; $j<= mt_rand(3, 5); $j++) {
                $critique = new Critique();

                $content = '<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>';

                $critique->setTitle($faker->sentence())
                    ->setContent($content)
                    ->setImage($faker->imageUrl())
                    ->setCreatedAt($faker->dateTimeBetween('-3months'))
                    ->setCategory($category);
                $manager->persist($critique);

                for($k=1 ;$k<= mt_rand(2, 6);$k++){
                    //On donne des commentaires entre 2 et 6 par critique
                    $comment = new Comment();
                    $content = '<p>' . join($faker->paragraphs(1), '</p><p>') . '</p>';
                    $days =(new \DateTime())->diff($critique->getCreatedAt())->days;

                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween('-' . $days . ' days'))
                            ->setCritique($critique);
                $manager->persist($comment);
                }

            }
        }
        $manager->flush();
    }
}
