@foreach ($items as $item)

    @if (!empty($item['children']))
        <li class="dropdown">
            <a href="{{ $item['route'] === '#' ? '#' : route($item['route']) }}">{{ $item['text'] }}</a>

            <ul class="dropdown-menu">
                @include('partials._nav_items', ['items' => $item['children']])
            </ul>
        </li>
    @else
        <li>
            <a href="{{ $item['route'] === '#' ? '#' : route($item['route']) }}">{{ $item['text'] }}</a>
        </li>
    @endif

@endforeach
