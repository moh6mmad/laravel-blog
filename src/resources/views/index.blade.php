@extends(config('laravel-blog.views.base'))
@section(config('laravel-blog.views.target_yield'))
<div class="md:grid md:grid-cols-3 md:gap-4">
    @foreach ($pages as $index=>$post)
    @if (!$index)
    <div class="md:flex col-span-3">
        <a href="{{route('laravel-blog.show', ['id'=>$post->id, 'slug'=>$post->slug])}}">
            <Div class="flex-1 md:pl-8  ">
                <h1 class="md:max-w-md text-2xl font-bold text-slate-900 dark:text-slate-200 md:text-3xl "> {{$post->title}}
                </h1>
                <div class="mt-8 capitalize text-sm flex space-x-2 dark:text-slate-400">
                    <div class="bg-black uppercase px-2 md:px-5 text-white"> {{$post->category_id}} </div>
                    <div class="px-1 md:px-4"> {{$post->created_at->diffForHumans()}}</div>
                </div>
            </div>
        </a>
        <div class="flex-1">
            <img class="max-w-full w-full flex-1 bg-gray-100 mb-4 block mx-auto md:mb-12 min-h-[300px] img-fluid"
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