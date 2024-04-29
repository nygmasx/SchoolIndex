<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class DeleteModal
{
    public string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }
}