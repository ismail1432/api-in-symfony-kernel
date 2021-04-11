<?php

namespace App\Domain\Model;

class BookTitle
{
    private string $name;

    public function __construct(string $name)
    {
        if(empty($name)) {
            throw new \InvalidArgumentException("Name cannot be empty");
        }

        $this->name = $name;
    }

    public function getValue(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}