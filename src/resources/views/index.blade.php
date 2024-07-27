@extends(config('laravel-blog.views.base'))
@section(config('laravel-blog.views.target_yield'))
<div class="md:grid md:grid-cols-3 md:gap-4">
    @foreach ($pages as $index=>$post)
    @if (!$index)
    <div class="md:flex col-span-3">
        <a href="{{route('laravel-blog.show', ['id'=>$post->id, 'slug'=>$post->slug])}}">
            <Div class="flex-1 md:pl-8  ">
                <h1 class="larablog-index-title "> {{$post->title}}
                </h1>
                <div class="larablog-index-excerpt ">
                    <div class="bg-black uppercase px-2 md:px-5 text-white"> {{$post->category_id}} </div>
                    <div class="px-1 md:px-4"> {{$post->created_at->diffForHumans()}}</div>
                </div>
            </div>
        </a>
        <div class="flex-1">
            <img class="larablog-index-image "
                src="{{ $post->primary_image_url }}" alt="{{$post->title}}">
        </div>
    </div>
    @else
    @include('larablog::widget', ['blog' => $post])
    @endif
    @endforeach
</div>
<div class="py-5 pagination">
    {!! $pages->onEachSide(5)->links() !!}
</div>
@endsection