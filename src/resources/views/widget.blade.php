<div class="">
    <a href="{{route('laravel-blog.show', ['id'=>$blog->id, 'slug'=>$blog->slug])}}">
        <div class="larablog-widget-image "
            style="background-image: url({{$blog->primary_image_url}});">
            <span
                class="absolute top-2 left-2 text-xs font-medium py-1 bg-opacity-80 bg-black uppercase px-2 mb-4 inline-block md:px-5 text-white">
                {{$post->category_id ?? 'GENERAL'}} </span>
        </div>
        <div class="">
            <h2 class="larablog-widget-title ">{{$blog->title}}</h2>
            <div class="larablog-widget-excerpt ">{!! $blog->excerpt !!}</div>
            <div class="underline">{{trans('index.read_more')}}</div>
        </div>
    </a>
</div>