<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class MarketingLayout extends Component
{
    /**
     * @param  array<string, mixed>  $seo
     * @param  array<int, array<string, mixed>>  $schemas
     */
    public function __construct(
        public array $seo = [],
        public array $schemas = [],
    ) {}

    public function render(): View
    {
        return view('layouts.marketing');
    }
}
