@extends('layouts.app')

@section('content')
<div class="container mt-4">
     <form action="{{ route('student.lesson.complete') }}" method="POST">
        @csrf
        <h2>{{ $lesson->title }}</h2>
        <p>{{ $lesson->description }}</p>

        @if($lesson->content_type === 'Video')
            <iframe width="640" height="360" src="{{ $lesson->source_url }}" frameborder="0" allowfullscreen></iframe>
        @else
            <a href="{{ asset('storage/'.$lesson->source_url) }}" target="_blank" class="btn btn-primary">Open Document</a>
        @endif

        <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
        <input type="hidden" name="class_id" value="{{ $lesson->class_id }}">

        <div class="mt-3">
            <button class="btn btn-primary mt-2" id="completeBtn" type="submit">Mark as Complete</button>
        </div>
    </form>
</div>

@endsection

