<div class="table-responsive">
    <table {{ $attributes->merge(['class' => 'table table-sm table-hover']) }}>
        {{ $slot }}
    </table>
</div>
