<?php

namespace App\Genesis\Actions;

use App\Genesis\Genesis;

class AskQuestionToGpt3
{
    use AsAction;

    public function __invoke(string $question): ?string
    {
        $genesis = new Genesis();
        $response = $genesis->client()->post('/ask', ['question' => $question])->json();
        $answer = $response['answer'] ?? null;

        return $answer;
    }
}
