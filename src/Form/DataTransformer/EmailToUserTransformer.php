<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EmailToUserTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        dd('transform', $value);
    }

    public function reverseTransform($value)
    {
        dd('reverse transform', $value);
    }
}