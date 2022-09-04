<?php

namespace App\Genesis;

use Exception;
use Illuminate\Support\Str;

class StepCondition
{
    public function __construct(
        public readonly Variable $variable,
        public readonly string $operator,
        public readonly array $values,
    ) {
    }

    public function isMet(): bool
    {
        switch ($this->operator) {
            case 'is':
                return in_array($this->variable->value(), $this->values);
                break;
            case 'is_not':
                return ! in_array($this->variable->value(), $this->values);
                break;
            case 'contains':
                foreach ($this->values as $value) {
                    if (Str::contains($this->variable->value(), $value)) {
                        return true;
                    }
                }
                break;
            case 'not_contains':
                foreach ($this->values as $value) {
                    if (! Str::contains($this->variable->value(), $value)) {
                        return true;
                    }
                }
                break;
            case 'in':
                return in_array($this->variable->value(), $this->values);
                break;
            case 'not_in':
                return ! in_array($this->variable->value(), $this->values);
                break;
            case 'is_empty':
                return empty($this->variable->value());
                break;
            case 'is_not_empty':
                return ! empty($this->variable->value());
                break;
            case 'is_true':
                return $this->variable->value() === true;
                break;
            case 'is_false':
                return $this->variable->value() === false;
                break;
            default:
                throw new Exception('Invalid operator: '.$this->operator);
        }

        return false;
    }
}
