<?php

namespace App\Domain\Model;

final class Book
{
    private BookId $id;
    private BookTitle $title;
    private string $content;

    private function __construct()
    {
    }

    public static function create(BookId $id, BookTitle $title, string $content): self
    {
        $book = new self();

        $book->id = $id;
        $book->title = $title;
        $book->content = $content;

        return $book;
    }

    public function getId(): BookId
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return (string) $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}