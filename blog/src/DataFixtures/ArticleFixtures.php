<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i < 11; $i++){
            $article = new Article();
            $article->setTitle("Article $i");
            $article->setContent("Antoine le margoulin !");
            $article->setCreatedAt(new \DateTime());
            $article->setUpdatedAt(new \DateTime());
            $article->setSlug("article-$i");
            $article->setIsPublished(true);
            $manager->persist($article);
        }
        

        $manager->flush();
    }
}
