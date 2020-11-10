@extends('layouts.professor')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success">Professor Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Welcome Professor {{ session('auth')['first_name'] }} {{ session('auth')['last_name'] }}.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
