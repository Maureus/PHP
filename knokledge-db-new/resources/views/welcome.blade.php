@extends('.layouts.app')
@section('content')
{{--    <div class="flex py-4 border-b border-white-300">--}}
{{--        <div class="container mx-auto flex justify-between">--}}
{{--            <div class="flex">--}}
{{--                <router-link class="mr-4 text-white" to="/" exact>Home</router-link>--}}
{{--                <router-link class="mr-4 text-white" to="/about">About</router-link>--}}
{{--            </div>--}}
{{--            <div class="flex">--}}
{{--                <router-link class="mr-4 text-white" to="/login" exact>Login</router-link>--}}
{{--                <router-link class="mr-4 text-white" to="/register">Register</router-link>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <navbar-component></navbar-component>
    <div class="container mx-auto">
        <router-view>
        </router-view>
    </div>
@endsection
