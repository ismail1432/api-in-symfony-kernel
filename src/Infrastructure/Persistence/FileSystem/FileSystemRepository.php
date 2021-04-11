<?php

namespace App\Infrastructure\Persistence\FileSystem;

use App\Domain\Model\Book;
use App\Domain\Model\BookId;
use App\Infrastructure\Persistence\BookRepositoryInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;


class FileSystemRepository implements BookRepositoryInterface
{
    private FilesystemAdapter $filesystemAdapter;

    public function __construct()
    {
        $this->filesystemAdapter = new FilesystemAdapter();
    }

    public function get(BookId $bookId): Book
    {
        /** @var ItemInterface $item */
        $item = $this->filesystemAdapter->getItem($bookId->getValue());
        if($item->isHit()) {
            return $item->get();
        }

        throw new \RuntimeException(sprintf("There is no Book, for ID: %s", $bookId->getValue()));
    }

    public function save(Book $book): Book
    {
        /** @var ItemInterface $item */
        $item = $this->filesystemAdapter->getItem($book->getId()->getValue());

        $item->set($book);

        return $book;
    }

    public function remove(BookId $bookId)
    {
        /** @var ItemInterface $item */
        $item = $this->filesystemAdapter->getItem($bookId->getValue());
        if(!$item->isHit()) {
            throw new \RuntimeException(sprintf("There is no Book, for ID: %s", $bookId->getValue()));
        }

        $this->filesystemAdapter->delete($bookId->getValue());
    }

    public function getAll(): iterable
    {
        return $this->filesystemAdapter->getItems();
    }
}