@extends('layouts.app')

@section('title', 'Login Page')

@section('content')
    <div class="login-container">
        <h2>💸 Cash Transfer System</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf <!-- Add this line for CSRF protection -->
            
            <label for="username">Username</label>
            <input type="text" id="name" name="name" placeholder="Enter username" required value="{{ old('username') }}">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter password" required>

            <div class="extras">
                <label><input type="checkbox" name="remember"> Remember Me</label>
            </div>

            <button type="submit">Login</button>
        </form>

        <!-- Display validation errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </div>
@endsection