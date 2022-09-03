<?php

namespace App\Genesis;

class CustomCommand {
    public readonly array $variables;
    public readonly array $steps;

    public function __construct(
        public readonly string $signature,
        public readonly ?string $description,
        private array $variables_,
        private array $steps_,
    ) {
        $this->variables = array_map(function ($variable) {
            return Variable::from($variable);
        }, $this->variables_);

        $this->steps = array_map(function ($step) {
            return Step::from($step, $this->variables);
        }, $this->steps_);
    }

    public static function from(array $command)
    {
        return new CustomCommand(
            signature: $command['identifier'],
            description: $command['description'] ?? null,
            variables_: $command['variables'] ?? [],
            steps_: $command['steps'] ?? [],
        );
    }
}
