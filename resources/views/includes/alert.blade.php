<?php $alert = session()->get('alert'); ?>

@if (isset($alert))
    @switch($alert['type'])
        @case('success')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i>
                {{ !isset($alert['message']) ? '_' : $alert['message'] }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @break

        @case('danger')
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-octagon me-1"></i>
                {{ !isset($alert['message']) ? '_' : $alert['message'] }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @break

        @default
    @endswitch
@endif
