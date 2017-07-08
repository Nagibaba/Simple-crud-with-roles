@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{$post->title}} <span class="pull-right">{{$post->created_at->diffForHumans()}}</span></div>
                <center class="green">{{isset($message)?$message:null}}</center>
                <div class="panel-body">
                    <center>{{isset($message)?$message:null}}</center>
                    <p><span class="capimg"><img src="{{$post->img_src ? url('images/'.$post->img_src) : 'http://lorempixel.com/400/300'}}" class="img img-responsive" width="400px" height="300px">{{!$post->img_src?'Image was automaticially generated':''}}</span>{{$post->content}} </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
