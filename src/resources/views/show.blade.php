@extends(config('laravel-blog.views.base'),[
'title' => $page->title,
'description' => \Illuminate\Support\Str::limit(strip_tags($page->content), 160),
'image' => $page->primary_image_url,
'url' => route('laravel-blog.show', ['id'=>$page->id,'slug'=>$page->slug]),
])
@section(config('laravel-blog.views.target_yield'))
<div class=" larablog-container ">
    <div class="space-y-2 md:pt-6 pb-8 md:space-y-5">
        <div class="py-2 max-w-2xl mx-auto md:py-8 items-start md:pl-8 my-12 flex flex-col md:space-x-8 md:flex-row">
            <Div class="flex-1">
                <h1 class="larablog-show-title  ">
                    {{$page->title}}
                </h1>
                <div class="mt-8 capitalize divide-x divide-x-black text-sm flex space-x-2 dark:text-slate-400">
                    <div class="bg-black uppercase px-2 md:px-5 text-white"> {{$page->category_id}} </div>
                    <div class="px-1 md:px-4">{{$page->estimate_reading}} read</div>
                    <div class="px-1 md:px-4"> {{$page->created_at->diffForHumans()}}</div>
                </div>
            </div>
        </div>
        <img width="100%" height="100%" class="larablog-show-image " src="{{ $page->primary_image_url }}" alt="{{$page->title}}">
        <div class="larablog-show-content max-w-2xl mx-auto px-2 md:px-0 py-4">
            {!! $page->content !!}
        </div>
    </div>
    <div class="related-posts grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-3">
        @foreach($page->related_posts as $post)
        @include('larablog::widget', ['blog' => $post])
        @endforeach
    </div>
</div>
@endsection