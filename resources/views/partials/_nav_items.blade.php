@foreach ($items as $item)
    @if (!empty($item['children']))
        <li class="dropdown">
            <a href="{{ $item['route'] === '#' ? '#' : route($item['route']) }}">{{ $item['text'] }}</a>

            <ul class="dropdown-menu">
                {{-- This is the recursion! We call the same partial for the children --}}
                @include('partials._nav_items', ['items' => $item['children']])
            </ul>
        </li>
    @else
        <li>
            <a href="{{ $item['route'] === '#' ? '#' : route($item['route']) }}">{{ $item['text'] }}</a>
        </li>
    @endif
@endforeach
