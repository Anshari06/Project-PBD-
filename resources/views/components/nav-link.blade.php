@props(['active' => false, 'icn' => ''])

<a {{ $attributes }}
    class="nav-link {{ $active ? 'bg-primary text-light active' : 'text-light' }}"
    aria-current="{{ $active ? 'page' : false }}">
    <i class="{{ $icn }}"></i> {{ $slot }}
</a>