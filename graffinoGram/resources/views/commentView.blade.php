@extends('master')

@section('content')
    <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
        <div class="grid grid-cols-1">
            <div class="p-6">

                <div class="flex items-center">
                    <img src="{{$post->user->profile_picture}}" width="30" height="30" class="profile-picture">
                    <div class="ml-4 text-lg leading-7 font-semibold"><a href="#" class="text-gray-900 dark:text-white">{{$post->user->username}}'s post</a></div>
                </div>

                @for ($i=0; $i<count($usernames); $i++)  
                <div class="flex items-center">
                    <div class="text-gray-900 dark:text-white">{{$usernames[$i]}}: </div>
                    <div class="text-gray-900 dark:text-white">{{$comments[$i]}}</div>
                </div>
                @endfor

            </div>                        
        </div>
    </div>
@endsection