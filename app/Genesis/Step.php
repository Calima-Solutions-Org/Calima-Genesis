<?php

namespace App\Genesis;

class Step
{
    public function __construct(
        public readonly string $name,
        public readonly string $command,
        public readonly bool $isRequired,
        public readonly array $conditions,
    ) {

    }

    public static function from(array $step, array $variables, ?string $basePath = null)
    {
        $variables = collect($variables);
        return new Step(
            name: $step['name'],
            command: $basePath ? 'cd "' . $basePath . '" && ' . $step['command'] : $step['command'],
            isRequired: $step['required'] ?? false,
            conditions: array_values(array_filter(array_map(function ($condition) use ($variables) {
                $variable = $variables->where('name', $condition['variable'])->first();
                if ($variable instanceof Variable) {
                    return new StepCondition(
                        variable: $variable,
                        operator: $condition['operator'],
                        values: $condition['values']
                    );
                }
            }, $step['conditions'] ?? []))),
        );
    }

    public function meetsConditions(): bool
    {
        if ($this->isRequired) {
            return true;
        }

        foreach ($this->conditions as $condition) {
            if (! $condition->isMet()) {
                return false;
            }
        }

        return true;
    }

    public function command(array $variables = []): string
    {
        $genesisCommand = config('app.env') === 'production' ? 'genesis' : 'php genesis';
        $command = preg_replace('/\{\{([\s]*)genesis([\s]*)\}\}/', $genesisCommand, $this->command);
        $command = preg_replace_callback('/\{\{([\s]*)([\w_-]+)([\s]*)\}\}/', function ($matches) use ($variables) {
            $variable = collect($variables)->where('name', $matches[2])->first();
            return $variable ? $variable->value() : $matches[2];
        }, $command);

        return $command;
    }
}
