<?php

namespace App\Http\Controllers;

use App\Services\ResumeAiWriter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResumeAiController extends Controller
{
    public function __construct(protected ResumeAiWriter $writer) {}

    public function generate(Request $request): JsonResponse
    {
        if (! $request->user()->hasPlanAccess()) {
            return response()->json([
                'message' => 'Please subscribe to a plan to use AI writing.',
            ], 403);
        }

        $validated = $request->validate([
            'field' => ['required', 'string', 'in:headline,summary,skills,experience_bullets,project_description,languages,full_resume'],
            'context' => ['nullable', 'array'],
            'index' => ['nullable', 'integer', 'min:0'],
        ]);

        $context = array_merge($validated['context'] ?? [], [
            'index' => $validated['index'] ?? ($validated['context']['index'] ?? 0),
        ]);

        $field = $validated['field'];
        $content = $this->writer->generate($field, $context);

        if ($field === 'full_resume') {
            $decoded = json_decode($content, true);
            if (is_array($decoded)) {
                return response()->json([
                    'field' => $field,
                    'content' => $decoded,
                    'ai_powered' => $this->writer->isConfigured(),
                    'provider' => $this->writer->providerLabel(),
                    'model' => $this->writer->modelLabel(),
                ]);
            }
        }

        return response()->json([
            'field' => $field,
            'content' => $content,
            'ai_powered' => $this->writer->isConfigured(),
            'provider' => $this->writer->providerLabel(),
            'model' => $this->writer->modelLabel(),
        ]);
    }
}
