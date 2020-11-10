@extends('layouts.app')

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
                                <th>Course Subject</th>
                                <th>Course Name</th>
                                <th>Professor Name</th>
                                <th>Send Request</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $index => $course)
                                @if($course->exists())
                                @php 
                                    $join_requests = collect($course->data()['join_requests'] ?? []); 
                                    $status = $join_requests->where('student_id', session('auth')['id'])->pluck('status')->toArray();
                                    $status = !empty($status) ? $status[0] : "";
                                @endphp
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td>{{ $course->data()['crn'] }}</td>
                                        <td>{{ $course->data()['subject'] }}</td>
                                        <td>{{ $course->data()['name'] }}</td>
                                        <td>@isset($course->data()['professor_id']) {{ $profs[$course->data()['professor_id']] }} @endisset</td>
                                        @if(in_array(session('auth')['id'], array_column(($course->data()['join_requests'] ?? []), 'student_id')))
                                            <td><label class="badge {{ $status == 'Approved' ? 'badge-success' : ($status == 'Pending' ? 'badge-primary' : 'badge-danger') }}">{{ $status }}</label></td>
                                        @else
                                            <td><a class="badge badge-primary" href="{{ route('course.request', $course->id()) }}">Send Request</a></td>
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