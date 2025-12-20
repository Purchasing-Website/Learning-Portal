@extends('layouts.Custom_app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->gender }}</td>
                            <td>{{ $user->date_of_birth }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ Str::limit($user->address,50) }}</td>
                            <td>{{ $user->created_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No programs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $users->links() }}
            </div>


            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admins as $admin)
                        <tr>
                            <td>{{ $loop->iteration + ($admins->currentPage() - 1) * $admins->perPage() }}</td>
                            <td>{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>{{ $admin->gender }}</td>
                            <td>{{ $admin->date_of_birth }}</td>
                            <td>{{ $admin->phone }}</td>
                            <td>{{ Str::limit($admin->address,50) }}</td>
                            <td>{{ $admin->created_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No programs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $admins->links() }}
            </div>
            
        </div>
    </div>
</div>
@endsection