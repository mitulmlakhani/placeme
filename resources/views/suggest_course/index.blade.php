@extends('layouts.app')

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
                                <th>Like</th>
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
                                        @if(in_array(session('auth')['id'], $course->data()['likes'] ?? []))
                                            <td><label class="badge badge-secondary">Liked</label></td>
                                        @else
                                            <td><a class="badge badge-primary" href="{{ route('course.suggession.like', $course->id()) }}">Like</a></td>
                                        @endif
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