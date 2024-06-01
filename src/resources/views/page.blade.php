@extends(config('laravel-blog.views.base'))
@section('content')
<div class="p-container py-8 flex flex-col text-left flex-auto">
    {!! $page->content !!}
</div>
@endsection