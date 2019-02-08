<?php

namespace App\Service;

class MarkdownHelper
{
    public function parse(string $source): string
    {
        $item = $cache->getItem('markdown_'.md5($source));
        if (!$item->isHit()) {
            $item->set($markdown->transform($source));
            $cache->save($item);
        }

        return $item->get();
    }
}
