@if($breadcrumbs->isNotEmpty())
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @php
                $last = $breadcrumbs->pop();
            @endphp
            @foreach($breadcrumbs as $bc)
                <li class="breadcrumb-item"><a href="{{$bc->url}}">{{$bc->value}}</a></li>
            @endforeach
            <li class="breadcrumb-item active" aria-current="page">{{$last->value}}</li>
        </ol>
    </nav>
@endif