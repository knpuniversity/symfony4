<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Common\Persistence\ObjectManager;

class TagFixture extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Tag::class, 10, function(Tag $tag) {
            $tag->setName($this->faker->realText(20));
        });

        $manager->flush();
    }
}
