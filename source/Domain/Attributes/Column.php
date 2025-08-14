<?php

namespace Source\Domain\Attributes;

use Attribute;

#[Attribute]
class Column
{
    public function __construct(
        public string $name,
        public string $type,
        public ?string $customConverter = null, // Exemplo: 'DateTime'
        public ?bool $noHydrate = false,
        public ?bool $primaryKey = false,
        public ?bool $protected = false,
        public ?bool $required = false,
        public ?string $requiredMessage = null
    ) {}
}
