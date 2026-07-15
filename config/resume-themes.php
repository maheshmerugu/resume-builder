<?php

$colorPalette = [
    'indigo' => ['label' => 'Indigo', 'primary' => '#4f46e5', 'primary_dark' => '#3730a3', 'light' => '#eef2ff', 'accent' => 'indigo'],
    'violet' => ['label' => 'Violet', 'primary' => '#7c3aed', 'primary_dark' => '#5b21b6', 'light' => '#ede9fe', 'accent' => 'violet'],
    'blue' => ['label' => 'Blue', 'primary' => '#2563eb', 'primary_dark' => '#1d4ed8', 'light' => '#dbeafe', 'accent' => 'blue'],
    'teal' => ['label' => 'Teal', 'primary' => '#0d9488', 'primary_dark' => '#0f766e', 'light' => '#ccfbf1', 'accent' => 'teal'],
    'emerald' => ['label' => 'Emerald', 'primary' => '#059669', 'primary_dark' => '#047857', 'light' => '#d1fae5', 'accent' => 'emerald'],
    'amber' => ['label' => 'Amber', 'primary' => '#d97706', 'primary_dark' => '#b45309', 'light' => '#fef3c7', 'accent' => 'amber'],
    'rose' => ['label' => 'Rose', 'primary' => '#e11d48', 'primary_dark' => '#be123c', 'light' => '#ffe4e6', 'accent' => 'rose'],
    'slate' => ['label' => 'Slate', 'primary' => '#475569', 'primary_dark' => '#334155', 'light' => '#f1f5f9', 'accent' => 'slate'],
    'stone' => ['label' => 'Stone', 'primary' => '#57534e', 'primary_dark' => '#44403c', 'light' => '#f5f5f4', 'accent' => 'stone'],
];

$layoutPalette = [
    'modern' => ['label' => 'Modern', 'category' => 'Tech & Startup', 'tagline' => 'Tech'],
    'classic' => ['label' => 'Classic', 'category' => 'Professional', 'tagline' => 'Corporate'],
    'minimal' => ['label' => 'Minimal', 'category' => 'Minimal', 'tagline' => 'Clean'],
    'banner' => ['label' => 'Banner', 'category' => 'Creative', 'tagline' => 'Bold'],
    'underline' => ['label' => 'Underline', 'category' => 'Executive', 'tagline' => 'Executive'],
    'boxed' => ['label' => 'Boxed', 'category' => 'Academic', 'tagline' => 'Formal'],
];

$legacyIds = [
    'modern' => ['layout' => 'modern', 'color' => 'indigo'],
    'classic' => ['layout' => 'classic', 'color' => 'stone'],
    'minimal' => ['layout' => 'minimal', 'color' => 'slate'],
];

$themes = [];

foreach ($layoutPalette as $layoutKey => $layout) {
    foreach ($colorPalette as $colorKey => $color) {
        $id = "{$layoutKey}-{$colorKey}";
        foreach ($legacyIds as $legacyId => $pair) {
            if ($pair['layout'] === $layoutKey && $pair['color'] === $colorKey) {
                $id = $legacyId;
                break;
            }
        }

        $themes[$id] = [
            'id' => $id,
            'label' => "{$layout['label']} {$color['label']}",
            'description' => "{$layout['label']} layout with {$color['label']} accents — ATS-friendly and print-ready.",
            'tagline' => $layout['tagline'],
            'category' => $layout['category'],
            'layout' => $layoutKey,
            'font' => $layoutKey === 'classic' ? 'serif' : 'sans',
            'accent' => $color['accent'],
            'colors' => [
                'primary' => $color['primary'],
                'primary_dark' => $color['primary_dark'],
                'light' => $color['light'],
                'text' => '#111827',
                'muted' => '#6b7280',
                'border' => '#e5e7eb',
            ],
            'featured' => in_array($id, ['modern', 'classic', 'minimal', 'banner-indigo', 'underline-emerald', 'boxed-slate'], true),
        ];
    }
}

return [
    'themes' => $themes,
    'legacy' => array_keys($legacyIds),
];
