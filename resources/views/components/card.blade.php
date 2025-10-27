@props(
    ['highlight' => false]
)

<div @class(['card'])>
    {{ $slot }}
    <a class="button" href="{{ $attributes->get('href') }}">Sprawdź szczegóły ucznia</a>
</div>