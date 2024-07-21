<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormInput extends Component
{
    public string $label;

    public string $name;

    public ?string $type;

    public ?string $defaultValue = null;

    public bool $required = false;

    public ?string $class = null;

    /**
     * Create a new component instance.
     */
    public function __construct(string $label, string $name, string $type = null, string $defaultValue = null, bool $required = false, string $class = null)
    {
        $this->label = $label;
        $this->name = $name;
        $this->type = $type;
        $this->defaultValue = $defaultValue;
        $this->required = $required;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form-input');
    }
}
