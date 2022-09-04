<?php

namespace App\Genesis;

class ProjectType
{
    public readonly array $variables;

    public readonly array $steps;

    public function __construct(
        public readonly string $identifier,
        public readonly string $name,
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

    public static function from(array $projectType)
    {
        return new ProjectType(
            identifier: $projectType['identifier'],
            name: $projectType['name'],
            variables_: $projectType['variables'] ?? [],
            steps_: $projectType['steps'] ?? [],
        );
    }
}
