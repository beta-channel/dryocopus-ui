<div id="search" class="card">
    <div class="card-header">
        <h5 class="text-primary mb-0">検索</h5>
    </div>
    <div class="card-body">
        <form method="get" action="{{ $action }}">
            {{ $slot }}
            <div>
                <button type="submit" class="btn btn-primary btn-sm px-5 float-end">検索</button>
            </div>
        </form>
    </div>
</div>
