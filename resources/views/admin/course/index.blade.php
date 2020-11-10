@extends('layouts.admin')

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
                                <th>Professor Name</th>
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
                                        <td>@isset($course->data()['professor_id']) {{ $profs[$course->data()['professor_id']] }} @endisset</td>
                                        <td>@if($course->data()['is_approved'] == 1) 
                                                <span class="badge badge-success">Approved</span> 
                                            @elseif($course->data()['is_approved'] == 2) 
                                                <span class="badge badge-danger">Rejected</span>
                                            @else 
                                                <span class="badge badge-primary">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($course->data()['is_approved'] == 0)
                                            <a class="badge badge-success" href="{{ route('admin.course.approve', $course->id()) }}">Approve</a>
                                            <a class="badge badge-danger" href="{{ route('admin.course.reject', $course->id()) }}">Reject</a>
                                            @endif
                                            <a class="badge badge-primary" href="{{ route('admin.course.view', $course->id()) }}">View Join Requests</a>
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