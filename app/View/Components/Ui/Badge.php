<?php

declare(strict_types=1);

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Badge extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $color = 'gray',
        public string $text = '',
        public string $size = 'xs'
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.badge');
    }
}
