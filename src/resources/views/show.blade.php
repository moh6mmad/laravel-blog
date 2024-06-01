@extends(config('laravel-blog.views.base'),[
'title' => $page->title,
'description' => \Illuminate\Support\Str::limit(strip_tags($page->content), 160),
'image' => $page->primary_image_url,
'url' => route('blog.show', ['id'=>$page->id,'slug'=>$page->slug]),
'bg' => 'bg-white'
])@section('content')
<div class="p-container relative px-4 md:px-0 blog-content">
    <div class="space-y-2 md:pt-6 pb-8 md:space-y-5">
        <div class="py-2 md:py-8 items-start  md:border-l border-black md:pl-8 my-12 flex flex-col md:space-x-8 md:flex-row">
            <Div class="flex-1">
                <h1 class="md:max-w-md font-libre text-2xl font-bold text-slate-900 dark:text-slate-200 md:text-3xl ">
                    {{$page->title}}</h1>
                <div class="mt-8 capitalize divide-x divide-x-black text-sm flex space-x-2 dark:text-slate-400">

                    <div class="bg-black uppercase px-2 md:px-5 text-white">
                        {{$page->category_id}} </div>
                    <div class="px-1 md:px-4">{{$page->estimate_reading}}
                        read</div>
                    <div class="px-1 md:px-4"> {{$page->created_at->diffForHumans()}}</div>
                </div>
            </div>

        </div>

        <img width="100%" height="100%"
            class="max-w-[800px] w-full flex-1 bg-gray-100 mb-4 block mx-auto md:mb-12 min-h-[300px] block img-fluid"
            src="{{ $page->primary_image_url }}" alt="{{$page->title}}">
        <div class="max-w-[800px] mx-auto px-2 md:px-0 py-4">

            {!! $page->content !!}
        </div>


    </div>

    <div class='py-4'>
        <a class='inline-block mr-2 ' href='https://twitter.com/intent/tweet?text={{route('blog.show',
            ['id'=>$page->id,'slug'=>$page->slug])}}'
            rel='nofollow' target='_blank'>
            <i class='fab  text-gray-500 fa-twitter'></i>

        </a>
        <a class='inline-block mr-2 -facebook' href='https://www.facebook.com/sharer/sharer.php?u={{route('blog.show',
            ['id'=>$page->id,'slug'=>$page->slug])}}' rel='nofollow' target='_blank'>
            <i class='fab  text-gray-500 fa-facebook'></i>

        </a>
        <a class='inline-block mr-2 -linkedin' href='https://www.linkedin.com/cws/share?url={{route('blog.show',
            ['id'=>$page->id,'slug'=>$page->slug])}}' rel='nofollow' target='_blank'>
            <i class='fab  text-gray-500 fa-linkedin'></i>

        </a>
        <a class='inline-block mr-2 -reddit' href='http://www.reddit.com/submit?url={{route('blog.show',
            ['id'=>$page->id,'slug'=>$page->slug])}}&amp;title={{$page->title}}' rel='nofollow'
            target='_blank'>
            <i class='fab  text-gray-500 fa-reddit'></i>

        </a>
        <a class='inline-block mr-2 -mail' href='mailto:?subject={{$page->title}}&amp;amp;body={{route('blog.show',
            ['id'=>$page->id,'slug'=>$page->slug])}}' rel='nofollow' target='_blank'
            title='via email'>
            <i class="fas  text-gray-500 fa-envelope"></i>

        </a>
    </div>


    <div class="related-posts grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-3">

        @foreach($page->related_posts as $post)
        @include('blog.widget', ['blog' => $post])
        @endforeach
    </div>
    <a class="table bg-blue-100 text-blue-500 text-sm py-1 px-5 rounded-full my-4" href="/blog">&larr; Back to
        blog</a>


</div>
@endsection