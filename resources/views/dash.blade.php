@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')

<div class="row">
    @foreach ($enterprises as $enterprise)

    <div class="col-md-3">
        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle" src="{{ $enterprise->thumb }}"
                        alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">{{ $enterprise->name }}</h3>

                <p class="text-muted text-center">{{ $enterprise->situation }}</p>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>

    @endforeach
</div>

@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    console.log('Hi!');

</script>
@stop
