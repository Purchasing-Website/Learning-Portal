@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>My Classes</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Class Name</th>
                <th>Status</th>
                <th>Progress (%)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($classes as $index => $class)
            @php
                $enrollment = $class->students->first();
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $class->title }}</td>
                <td>
                    <span class="badge bg-{{ $enrollment->pivot->status == 'active' ? 'success' : ($enrollment->pivot->status == 'completed' ? 'primary' : 'secondary') }}">
                        {{ ucfirst($enrollment->pivot->status) }}
                    </span>
                </td>
                <td>{{ $enrollment->pivot->progress }}%</td>
                <td>
                    <a href="{{ route('student.class.lessons', $class->id) }}" class="btn btn-sm btn-primary">View Lessons</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
