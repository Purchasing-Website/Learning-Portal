@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>My Classes</h2>

    <table class="table table-bordered mt-3">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Class Name</th>
                <th>Status</th>
                <th>Progress</th>
                <th>Enrolled Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($classes as $index => $class)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $class->title }}</td>
                    <td>
                        <span class="badge bg-{{ 
                            $class->pivot->status === 'active' ? 'success' : 
                            ($class->pivot->status === 'completed' ? 'primary' : 'secondary') }}">
                            {{ ucfirst($class->pivot->status) }}
                        </span>
                    </td>
                    <td>{{ $class->pivot->progress }}%</td>
                    <td>{{ \Carbon\Carbon::parse($class->pivot->enrolled_at)->format('Y-m-d') }}</td>
                    <td>
                        @if($class->pivot->status === 'active')
                            <a href="#" class="btn btn-sm btn-primary">
                                View
                            </a>
                        @else
                            <button class="btn btn-sm btn-secondary" disabled>
                                Not Accessible
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No enrolled classes yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
