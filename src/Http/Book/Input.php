<?php

namespace App\Http\Book;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class Input
{
    public function __construct()
    {
    }

    /**
     * @var string|null
     * @Assert\NotBlank
     */
    private ?string $title;

    /**
     * @var string|null
     * @Assert\NotBlank
     */
    private ?string $content;

    public static function createFromRequest(Request $request): self
    {
        $data = \json_decode($request->getContent(), true);
        $input = new self();

        $input->title = $data['title'] ?? null;
        $input->content = $data['content'] ?? null;

        return $input;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}