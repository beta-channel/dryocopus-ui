<div {{ $attributes }} class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 500px">
        <div class="modal-content position-relative">
            <div class="position-absolute top-0 end-0 mt-2 me-2 z-index-1">
                <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base bg-transparent shadow-none" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ $slot }}
        </div>
    </div>
</div>
