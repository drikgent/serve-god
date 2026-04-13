@extends('layouts.app')

@section('content')
    <section class="content-panel narrow">
        <span class="eyebrow">Contact</span>
        <h1>Let people reach the studio.</h1>
        @if(session('status'))
            <p class="helper-text">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('contact.send') }}" class="contact-card">
            @csrf
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Your name" required>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email address" required>
            <textarea name="message" rows="6" placeholder="Message" required>{{ old('message') }}</textarea>
            @error('name')
                <p class="error-text">{{ $message }}</p>
            @enderror
            @error('email')
                <p class="error-text">{{ $message }}</p>
            @enderror
            @error('message')
                <p class="error-text">{{ $message }}</p>
            @enderror
            <button type="submit">Send inquiry</button>
        </form>
    </section>
@endsection
