<?php $date = Carbon\Carbon::yesterday()->format('Y-m-d'); ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<url>
   <loc>{{ url('/') }}</loc>
   <lastmod>{{$date}}</lastmod>
   <priority>0.8</priority>
</url>

<url>
  <loc>{{ url('creators') }}</loc>
  <lastmod>{{$date}}</lastmod>
  <priority>0.8</priority>
</url>

<url>
   <loc>{{ url('contact') }}</loc>
   <lastmod>{{$date}}</lastmod>
   <priority>0.8</priority>
 </url>

 <url>
    <loc>{{ url('blog') }}</loc>
    <lastmod>{{$date}}</lastmod>
    <priority>0.8</priority>
  </url>

  @foreach (App\Models\Blogs::all() as $post)
  <url>
     <loc>{{ url('blog/post', $post->id).'/'.$post->slug }}</loc>
     <lastmod>{{$date}}</lastmod>
     <priority>0.8</priority>
  </url>
   @endforeach

@foreach (Pages::all() as $page)
<url>
   <loc>{{ url('p', $page->slug) }}</loc>
   <lastmod>{{$date}}</lastmod>
   <priority>0.8</priority>
</url>
 @endforeach

 @foreach (Categories::where('status', 'on') as $category)
<url>
  <loc>{{ url('p', $category->slug) }}</loc>
  <lastmod>{{$date}}</lastmod>
  <priority>0.8</priority>
</url>
@endforeach

	@foreach (User::where('status','active')->where('verified_id', 'yes')->get() as $user )
<url>
   <loc>{{ url($user->username) }}</loc>
   <lastmod>{{$date}}</lastmod>
   <priority>0.8</priority>
</url>
   @endforeach
</urlset>
