<?php

namespace App\Genesis;

class CustomCommand {
    public readonly array $variables;
    public readonly array $steps;

    public function __construct(
        public readonly string $signature,
        public readonly ?string $description,
        private readonly array $variables_,
        private readonly array $steps_,
        private readonly ?string $basePath = null,
    ) {
        $this->variables = array_map(function ($variable) {
            return Variable::from($variable);
        }, $this->variables_);

        $this->steps = array_map(function ($step) {
            return Step::from($step, $this->variables, $this->basePath);
        }, $this->steps_);
    }

    public static function from(array $command, ?string $basePath = null)
    {
        return new CustomCommand(
            signature: $command['identifier'],
            description: $command['description'] ?? null,
            variables_: $command['variables'] ?? [],
            steps_: $command['steps'] ?? [],
            basePath: $basePath,
        );
    }
}
