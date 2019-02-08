<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class ArticleController
{
    public function homepage()
    {
        return new Response('OMG! My first page already! WOOO!');
    }
}
