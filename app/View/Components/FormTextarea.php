<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormTextarea extends Component
{
    public string $label;

    public string $name;

    public ?string $defaultValue = null;

    public bool $required = false;

    /**
     * Create a new component instance.
     */
    public function __construct(string $label, string $name, string $defaultValue = null, bool $required = false)
    {
        $this->label = $label;
        $this->name = $name;
        $this->defaultValue = $defaultValue;
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form-textarea');
    }
}
