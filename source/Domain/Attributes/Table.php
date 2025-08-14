<?php

namespace Source\Domain\Attributes;

use Attribute;

#[Attribute]
class Table
{
    public function __construct(public string $name)
    {
    }
}
