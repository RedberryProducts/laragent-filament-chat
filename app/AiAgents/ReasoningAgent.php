<?php

namespace App\AiAgents;

use LarAgent\Agent;
use LarAgent\Attributes\Tool;

class ReasoningAgent extends Agent
{
    protected $model = 'llama3.2:3b';

    protected $history = 'session';

    protected $provider = 'ollama';

    protected $tools = [];

    public function instructions()
    {
        return "You are a reasoning agent. Think through the user question, plan how to answer and try to Answer in detail.";
    }

    public function prompt($message)
    {
        return $message;
    }

    // #[Tool('Analyze the user query and write a message explaining the user query in detail.', [
    //     'queryExplanation' => 'The detailed explanation of the query',
    // ])]
    // public function think($queryExplanation)
    // {
    //     return $queryExplanation;
    // }


    #[Tool('Based on output of think tool - plan steps to answer the user query the best answer.', [
        'plan' => 'Step-by-step plan what to do to answer the user query',
    ])]
    public function writeAnswerPlan($plan)
    {
        return $plan;
    }
}
