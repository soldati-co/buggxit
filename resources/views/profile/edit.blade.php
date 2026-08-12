@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">
        Profile
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="container-wide px-4 sm:px-6 lg:px-8 mx-auto space-y-6">
            <div class="p-4 sm:p-8 bg-black/90 backdrop-blur-sm border border-gray-800 rounded-xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-black/90 backdrop-blur-sm border border-gray-800 rounded-xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-black/90 backdrop-blur-sm border border-gray-800 rounded-xl">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
