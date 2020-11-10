@extends('layouts.professor')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success"> Course</div>

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

                    <table class="table table-bordered">
                        <tr>
                            <th>Name</th>
                            <td>{{ $course->data()['name'] }}</td>
                        </tr>
                        <tr>
                            <th>Subject</th>
                            <td>{{ $course->data()['subject'] }}</td>
                        </tr>
                        <tr>
                            <th>CRN</th>
                            <td>{{ $course->data()['crn'] }}</td>
                        </tr>
                        <tr>
                            <th>created_at</th>
                            <td>{{ date('Y-m-d H:i:s', $course->data()['created_at']) }}</td>
                        </tr>
                    </table>

                    <h1>Student Requests</h1>

                    <table class="table table-striped">
                        <thead>
                            <tr></tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                                <tr>
                                    <td>{{ ++$index }}</td>
                                    <td>{{ $student['first_name'].' '.($student['middle_name'] ? ' '.$student['middle_name'].' ' : '').$student['last_name'] }}</td>
                                    <td>{{ $student['jr']['status'] }}</td>
                                    <td>
                                        @if($student['jr']['status'] === "Pending")
                                            <a class="badge badge-primary" href="{{ route('professor.course.approve', ['id' => $course->id(), 'user_id' => $student['jr']['student_id']]) }}">Approve</a>
                                            <a class="badge badge-danger" href="{{ route('professor.course.reject', ['id' => $course->id(), 'user_id' => $student['jr']['student_id']]) }}">Reject</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection