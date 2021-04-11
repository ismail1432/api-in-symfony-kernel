<?php

namespace App\Infrastructure\Persistence\Memory;

use App\Domain\Model\Book;
use App\Domain\Model\BookId;
use App\Infrastructure\Persistence\BookRepositoryInterface;

class BookRepository implements BookRepositoryInterface
{
    private array $books;

    public function get(BookId $bookId): Book
    {
        $book = $this->books[$bookId->getValue()] ?? null;

        if(null === $book) {
            throw new \RuntimeException(sprintf("There is no Book, for ID: %s", $bookId->getValue()));
        }

        return $book;
    }

    public function save(Book $book): Book
    {
        if(!array_key_exists($id = $book->getId()->getValue(), $this->books)) {
            $this->books[$id] = $book;
        }

        return $book;
    }

    public function getAll(): iterable
    {
       return $this->books;
    }

    public function remove(BookId $bookId)
    {
        $book = $this->books[$bookId->getValue()] ?? null;

        if(null === $book) {
            throw new \RuntimeException(sprintf("There is no Book, for ID: %s", $bookId->getValue()));
        }

        unset($this->books[$bookId->getValue()]);
    }
}