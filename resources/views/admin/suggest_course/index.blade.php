@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success">Course suggestionss</div>

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
                                <th>Total Likes</th>
                                <th>Suggested By</th>
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
                                        <td>{{ count($course->data()['likes'] ?? []) }}</td>
                                        <td>{{ $profs[$course->data()['student_id']] ?? "" }}</td>
                                        <td>
                                            <a class="badge badge-success" href="{{ route('admin.course.create') }}?crn={{ $course->data()['crn'] }}&subject={{ $course->data()['subject'] }}&name={{ $course->data()['name'] }}&sid={{$course->id()}}">Approve</a>
                                            <a class="badge badge-danger" href="{{ route('admin.course.suggession.reject', $course->id()) }}">Reject</a>
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