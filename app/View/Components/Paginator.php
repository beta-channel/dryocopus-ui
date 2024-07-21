<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\View\Component;

class Paginator extends Component
{
    public AbstractPaginator $paginator;

    public int $itemCount;

    public bool $showTotal;

    /**
     * Create a new component instance.
     */
    public function __construct(AbstractPaginator $paginator, int $itemCount = null, bool $showTotal = true)
    {
        $this->paginator = $paginator->withQueryString();
        $this->itemCount = $itemCount ?? config('app.page_item_count');
        $this->showTotal = $showTotal;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.paginator');
    }
}
