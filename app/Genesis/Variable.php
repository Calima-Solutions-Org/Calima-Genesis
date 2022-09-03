<?php

namespace App\Genesis;

class Variable {
    private $value = null;

    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly string $type,
        public readonly bool $isRequired,
        public readonly array $options,
    )
    {

    }

    public static function from(array $variable)
    {
        return new Variable(
            name: $variable['name'],
            description: $variable['description'],
            type: $variable['type'],
            isRequired: $variable['required'] ?? false,
            options: $variable['allowed_values'] ?? [],
        );
    }

    public function value(mixed $value = null)
    {
        if ($value === null) {
            return $this->value;
        }

        $this->value = $value;
    }
}
