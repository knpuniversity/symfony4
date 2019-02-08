<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends BaseFixture
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(Article::class, 10, function(Article $article, $count) {
            $article->setTitle('Why Asteroids Taste Like Bacon')
                ->setSlug('why-asteroids-taste-like-bacon-'.$count)
                ->setContent(<<<EOF
Spicy **jalapeno bacon** ipsum dolor amet veniam shank in dolore. Ham hock nisi landjaeger cow,
lorem proident [beef ribs](https://baconipsum.com/) aute enim veniam ut cillum pork chuck picanha. Dolore reprehenderit
labore minim pork belly spare ribs cupim short loin in. Elit exercitation eiusmod dolore cow
**turkey** shank eu pork belly meatball non cupim.

Laboris beef ribs fatback fugiat eiusmod jowl kielbasa alcatra dolore velit ea ball tip. Pariatur
laboris sunt venison, et laborum dolore minim non meatball. Shankle eu flank aliqua shoulder,
capicola biltong frankfurter boudin cupim officia. Exercitation fugiat consectetur ham. Adipisicing
picanha shank et filet mignon pork belly ut ullamco. Irure velit turducken ground round doner incididunt
occaecat lorem meatball prosciutto quis strip steak.

Meatball adipisicing ribeye bacon strip steak eu. Consectetur ham hock pork hamburger enim strip steak
mollit quis officia meatloaf tri-tip swine. Cow ut reprehenderit, buffalo incididunt in filet mignon
strip steak pork belly aliquip capicola officia. Labore deserunt esse chicken lorem shoulder tail consectetur
cow est ribeye adipisicing. Pig hamburger pork belly enim. Do porchetta minim capicola irure pancetta chuck
fugiat.
EOF
            );

            // publish most articles
            if ($this->faker->boolean(70)) {
                $article->setPublishedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            }

            $article->setAuthor('Mike Ferengi')
                ->setHeartCount($this->faker->numberBetween(5, 100))
                ->setImageFilename('asteroid.jpeg')
            ;
        });

        $manager->flush();
    }
}
