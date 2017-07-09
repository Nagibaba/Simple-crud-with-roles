@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Home</div>
                <center>{{isset($message)?$message:null}}</center>
                <div class="panel-body">
                    Laravel 5.4 CRUD operation with 3 roles repository.
                    Created by  < Babak Nagiyev /><br>
                    <a href="https://github.com/Nagibaba/Simple-crud-with-roles" target="_blank">Github page</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
