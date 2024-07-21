@if($showTotal)
    <span>総計：{{ number_format($paginator->total()) }}</span>
@endif
@if($paginator->lastPage() > 1)
    <ul {{ $attributes->merge(['class' => 'pagination pagination-sm justify-content-center mb-0']) }}>
        <li class="page-item{{ $paginator->currentPage() === 1 ? ' disabled' : null }}">
            <a class="page-link" href="{{ $paginator->previousPageUrl() }}">前へ</a>
        </li>
        @for($p = max(1, min($paginator->currentPage() - ($itemCount - 1) / 2, $paginator->lastPage() - $itemCount - 1)); $p <= max(min($paginator->lastPage(), $itemCount), min($paginator->lastPage(), $paginator->currentPage() + ($itemCount - 1) / 2)); $p++)
            <li class="page-item{{ $p === $paginator->currentPage() ? ' active' : null }}"><a class="page-link" href="{{ $paginator->url($p) }}">{{ $p }}</a></li>
        @endfor
        <li class="page-item{{ $paginator->currentPage() < $paginator->lastPage() ? null : ' disabled' }}">
            <a class="page-link" href="{{ $paginator->nextPageUrl() }}">次へ</a>
        </li>
    </ul>
@endif
