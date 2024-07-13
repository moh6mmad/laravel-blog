@extends(config('laravel-blog.views.base'),[
'title' => $page->title,
'description' => \Illuminate\Support\Str::limit(strip_tags($page->content), 160),
'image' => $page->primary_image_url,
'url' => route('laravel-blog.show', ['id'=>$page->id,'slug'=>$page->slug]),
])
@section(config('laravel-blog.views.target_yield'))
<div class="container relative px-4 md:px-0 blog-content">
    <div class="space-y-2 md:pt-6 pb-8 md:space-y-5">
        <div class="py-2 max-w-2xl mx-auto md:py-8 items-start md:pl-8 my-12 flex flex-col md:space-x-8 md:flex-row">
            <Div class="flex-1">
                <h1 class="text-center  text-2xl font-bold text-slate-900 dark:text-slate-200 md:text-3xl ">
                    {{$page->title}}
                </h1>
                <div class="mt-8 capitalize divide-x divide-x-black text-sm flex space-x-2 dark:text-slate-400">
                    <div class="bg-black uppercase px-2 md:px-5 text-white"> {{$page->category_id}} </div>
                    <div class="px-1 md:px-4">{{$page->estimate_reading}} read</div>
                    <div class="px-1 md:px-4"> {{$page->created_at->diffForHumans()}}</div>
                </div>
            </div>
        </div>
        <img width="100%" height="100%" class="max-w-2xl w-full flex-1 bg-gray-100 mb-4 mx-auto md:mb-12 min-h-[300px] block img-fluid"
            src="{{ $page->primary_image_url }}" alt="{{$page->title}}">
        <div class="max-w-2xl mx-auto px-2 md:px-0 py-4">
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