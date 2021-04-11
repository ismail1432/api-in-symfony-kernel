<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Model\Book;
use App\Domain\Model\BookId;

interface BookRepositoryInterface
{
    public function get(BookId $bookId): Book;

    public function save(Book $book): Book;

    public function remove(BookId $bookId);

    /**
     * @return iterable|Book[]
     */
    public function getAll(): iterable;
}