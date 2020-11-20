@extends('master')


@section('content')
    @foreach($posts as $post)
        <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
            <div class="grid grid-cols-1">
                <div class="p-6">
                    <div class="flex items-center">
                        <img src="{{$post->user->profile_picture}}" width="30" height="30" class="profile-picture">
                        <div class="ml-4 text-lg leading-7 font-semibold"><a href="#" class="text-gray-900 dark:text-white">{{$post->user->username}}</a></div>
                    </div>
                    
                    <div class="flex items-center">
                        <a href="/postView/{{$post->id}}"><img src="{{$post->image}}" width="{{$post->image_width}}" height="{{$post->image_height}}"></a>
                    </div>

                    <div class="ml-10">
                        <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                            @if($post->likes == "1")
                                {{$post->likes}} like
                                @else
                                {{$post->likes}}  likes
                            @endif
                        </div>
                    </div>

                    <div class="ml-11">
                        <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm" id="post-size">
                            <b>{{$post->user->username}}</b>  {{$post->caption}}
                        </div>
                    </div>

                    <div class="ml-12">
                        <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm" id="post-size">
                            <a href="%" class="underline">
                                @if($post->comment_id != null)
                                    View all {{$post->comment->count}} comments
                                    @else
                                    Be the first to comment!
                                @endif 
                            </a>
                        </div>
                    </div>
                </div>                        
            </div>
        </div>
    @endforeach
@endsection