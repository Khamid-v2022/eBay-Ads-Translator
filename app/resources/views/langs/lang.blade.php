@foreach($items as $item)
    <h1>{{ trans($item->getTitle()) }}</h1>
    <p>{{ trans($item->getDescription()) }}</p>
@endforeach

