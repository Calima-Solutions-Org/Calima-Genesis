<?php

namespace App\Genesis;

class FileTemplate
{
    public readonly array $variables;

    public readonly array $steps;

    public function __construct(
        public readonly string $identifier,
        public readonly string $content,
    ) {
    }

    public static function from(array $template)
    {
        return new FileTemplate(
            identifier: $template['identifier'],
            content: $template['content'],
        );
    }
}
