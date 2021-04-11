<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Doctrine\Book as DoctrineBook;
use App\Domain\Model\Book;
use App\Domain\Model\BookId;
use App\Domain\Model\BookTitle;
use App\Infrastructure\Persistence\BookRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository implements BookRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DoctrineBook::class);
    }

    public function get(BookId $bookId): Book
    {
        $book = $this->find($bookId->getValue());

        if (null === $book) {
            throw new \RuntimeException(sprintf("There is no Book, for ID: %s", $bookId->getValue()));
        }

        return Book::create(
            $bookId,
            new BookTitle($book->getTitle()),
            $book->getContent()
            );
    }

    public function remove(BookId $bookId)
    {
        throw new \Exception("Not Implemented Yet!");
    }

    public function save(Book $book): Book
    {
        throw new \Exception("Not Implemented Yet!");
    }

    public function getAll(): iterable
    {
        throw new \Exception("Not Implemented Yet!");
    }
}
