<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AI Provider (free options supported)
    |--------------------------------------------------------------------------
    |
    | Recommended FREE setup (OpenRouter — no credit card for free models):
    |   1. Sign up at https://openrouter.ai/
    |   2. Create API key at https://openrouter.ai/keys
    |   3. Set in .env:
    |        AI_PROVIDER=openrouter
    |        AI_API_KEY=sk-or-v1-your-key
    |        AI_MODEL=google/gemma-2-9b-it:free
    |
    | Other free options:
    |   - Groq: https://console.groq.com/keys  (AI_PROVIDER=groq)
    |   - local: built-in writer, no API key needed
    |
    */

    'provider' => env('AI_PROVIDER', 'openrouter'),

    'api_key' => env('AI_API_KEY', env('OPENAI_API_KEY')),

    'model' => env('AI_MODEL', env('OPENAI_MODEL')),

    'max_tokens' => (int) env('AI_MAX_TOKENS', env('OPENAI_MAX_TOKENS', 800)),

    'timeout' => (int) env('AI_TIMEOUT', env('OPENAI_TIMEOUT', 45)),

    'providers' => [
        'openrouter' => [
            'label' => 'OpenRouter (free models)',
            'base_url' => 'https://openrouter.ai/api/v1/chat/completions',
            'default_model' => 'google/gemma-2-9b-it:free',
            'signup_url' => 'https://openrouter.ai/keys',
            'fallback_models' => [
                'google/gemma-2-9b-it:free',
                'meta-llama/llama-3.2-3b-instruct:free',
                'qwen/qwen-2-5-3b-instruct:free',
                'mistralai/mistral-7b-instruct:free',
            ],
        ],
        'groq' => [
            'label' => 'Groq (free tier)',
            'base_url' => 'https://api.groq.com/openai/v1/chat/completions',
            'default_model' => 'llama-3.1-8b-instant',
            'signup_url' => 'https://console.groq.com/keys',
            'fallback_models' => [
                'llama-3.1-8b-instant',
                'gemma2-9b-it',
            ],
        ],
        'openai' => [
            'label' => 'OpenAI',
            'base_url' => 'https://api.openai.com/v1/chat/completions',
            'default_model' => 'gpt-4o-mini',
            'signup_url' => 'https://platform.openai.com/api-keys',
            'fallback_models' => [
                'gpt-4o-mini',
            ],
        ],
        'local' => [
            'label' => 'Built-in smart writer',
            'default_model' => 'local',
        ],
    ],

];
