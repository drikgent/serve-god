@extends('layouts.app')

@section('content')
    <section class="section-block">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Category</span>
                <h1 class="section-title">{{ $category->name }}</h1>
                <p>{{ $category->description }}</p>
            </div>
        </div>
    </section>

    @include('public.partials.feed')
@endsection
