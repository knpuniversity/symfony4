<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommentAdminController extends Controller
{
    /**
     * @Route("/admin/comment", name="comment_admin")
     */
    public function index()
    {
        return $this->render('comment_admin/index.html.twig', [
            'controller_name' => 'CommentAdminController',
        ]);
    }
}
