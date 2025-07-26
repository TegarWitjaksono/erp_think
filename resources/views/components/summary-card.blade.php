@props(['title', 'value', 'bg' => 'primary'])

<div class="col-md-3">
    <div class="card bg-{{ $bg }} text-white mb-3 shadow-sm">
        <div class="card-body text-center">
            <h5 class="card-title">{{ $title }}</h5>
            <h3 class="card-text">{{ $value }}</h3>
        </div>
    </div>
</div>
