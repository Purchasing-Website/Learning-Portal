@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Lessons: {{ $class->name }}</h2>

    <ul class="list-group">
        @foreach($class->lessons as $lesson)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            {{ $lesson->sequence }}. {{ $lesson->title }}
            <span>
                <span class="badge bg-{{ ($progress[$lesson->id] ?? 0) == 100 ? 'primary' : 'secondary' }}">
                    {{ $progress[$lesson->id] ?? 0 }}%
                </span>
                <a href="{{ route('student.lesson.start', $lesson->id) }}" class="btn btn-sm btn-success ms-2">
                    Start / Continue
                </a>
            </span>
        </li>
        @endforeach
    </ul>
</div>
@endsection

