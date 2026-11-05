@php
    $isOpen = in_array($node->id, $trailIds, true);
@endphp

<a href="{{ $node->url }}" data-category-link class="chk {{ $isOpen ? 'font-bold text-primary' : '' }}" style="padding-left: {{ $depth * 16 }}px" @if ($category?->id === $node->id) aria-current="page" @endif>
    <span>{{ $node->name }}</span>
</a>

@if ($isOpen)
    @foreach ($node->children as $child)
        @include('frontend.shop.partials.category_node', ['node' => $child, 'depth' => $depth + 1])
    @endforeach
@endif
