<?php

namespace App\Domain\Model;

class BookId
{
    private string $id;

    public function __construct(string $id)
    {
        if($id <= 0) {
            throw new \InvalidArgumentException("Id cannot be negative or equal to 0");
        }

        $this->id = $id;
    }

    public function getValue(): string
    {
        return $this->id;
    }
}