<?php

namespace App\Http\Book;

use App\Domain\Model\Book;

final class Output
{
    public static function toArray(Book $book): array
    {
        return [
            'id' => $book->getId()->getValue(),
            'title' => $book->getTitle(),
            'content' => $book->getContent(),
        ];
    }

    public static function createList(iterable $books): array
    {
        $data = [];
        foreach ($books as $book) {
            $data[] = self::toArray($book);
        }

        return $data;
    }
}