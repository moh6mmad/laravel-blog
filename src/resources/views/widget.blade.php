<div class="md:border-l border-black">
    <a href="{{route('blog.show', ['id'=>$blog->id, 'slug'=>$blog->slug])}}">

     

        <div class="bg-slate-100 mb-6 relative h-48 w-full block bg-cover bg-center" style="background-image: url({{$blog->primary_image_url}});">
        
            <span class="absolute top-2 left-2 text-xs font-medium py-1 bg-opacity-80 bg-black uppercase px-2 mb-4 inline-block md:px-5 text-white">
                {{$post->category_id ?? 'GENERAL'}} </span>
        </div>
        <div class="md:pl-5">
       
        <h2 class="font-libre min-h-12 font-medium tracking-tight py-3 md:py-5">{{$blog->title}}</h2>
        <div class="py-6 h-32">{!! $blog->excerpt !!}</div> 

        <div class="underline">{{trans('index.read_more')}}</div></div>

    </a>
</div>