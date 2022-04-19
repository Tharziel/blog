<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Tag;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {   

        $tagDefault = new Tag();
        $tagDefault->setName("Tag par défaut");
        $manager->persist($tagDefault);

        for($i = 1; $i < 11; $i++){
            $article = new Article();
            
            $article->setTitle("Article $i");
            $article->setContent("Antoine le margoulin !");
            $article->setCreatedAt(new \DateTime());
            $article->setUpdatedAt(new \DateTime());
            $article->setSlug("article-$i");
            $article->setIsPublished(true);
            $article->setCategory($this->getReference("cat".$i));
            $tag = new Tag();
            $tag->setName("Tag depuis l'article n°$i");
            $article->addTag($tag);
            $article->addTag($tagDefault);
            
            $manager->persist($tag);
            $manager->persist($article);
        }
        

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
