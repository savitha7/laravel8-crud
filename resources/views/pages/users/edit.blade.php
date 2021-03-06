@extends('layouts.app')
@section('header') 
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Users') }}
    </h2>
@endsection
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{ __('Edit the user') }}
                    <x-app-icon-link :href="route('users')" class="float-right">
                       <i class="fas fa-arrow-left fa-fw"></i>
                    </x-app-icon-link>
                </div>
            </div>

            <!--Card-->
             <div id='recipients' class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">             
                <form method="POST" action="{{ route('users.update',['user'=>request()->route('user')]) }}">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-label for="name" :value="__('Name')" />

                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{$name}}" required autofocus />
                    </div>

                    <!-- Email Address -->
                    <div class="mt-4">
                        <x-label for="email" :value="__('Email')" />

                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" value="{{$email}}" required />
                    </div>                    

                    <div class="flex items-center justify-end mt-4">
                        <x-button class="ml-4">
                            {{ __('Update the user') }}
                        </x-button>
                    </div>
                </form>               
            </div>
            <!--/Card-->
        </div>
    </div>
@endsection