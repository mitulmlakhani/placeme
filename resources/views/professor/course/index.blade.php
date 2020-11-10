@extends('layouts.professor')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success"> Courses</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif
                    @if (session('danger'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('danger') }}
                    </div>
                    @endif

                    <table class="table table-striped">
                        <thead>
                            <tr></tr>
                                <th>#</th>
                                <th>CRN</th>
                                <th>Subject</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $index => $course)
                                @if($course->exists())
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td>{{ $course->data()['crn'] }}</td>
                                        <td>{{ $course->data()['subject'] }}</td>
                                        <td>{{ $course->data()['name'] }}</td>
                                        <td>@if($course->data()['is_approved']) 
                                                <span class="badge badge-success">Approved</span> 
                                            @else 
                                                <span class="badge badge-danger">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('professor.course.view', $course->id()) }}">Join Requests</a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection