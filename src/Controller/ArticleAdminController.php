<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleAdminController extends AbstractController
{
    /**
     * @Route("/admin/article/new", name="admin_article_new")
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function new(EntityManagerInterface $em)
    {
        die('todo');

        return new Response(sprintf(
            'Hiya! New Article id: #%d slug: %s',
            $article->getId(),
            $article->getSlug()
        ));
    }

    /**
     * @Route("/admin/article/{id}/edit")
     * @IsGranted("MANAGE", subject="article")
     */
    public function edit(Article $article)
    {
        dd($article);
    }
}
