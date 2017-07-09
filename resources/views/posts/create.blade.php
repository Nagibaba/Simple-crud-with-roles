@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Create</div>
                
                <div class="panel-body">
                    @include('errors.errors')
                    <form action="{{url('posts')}}" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class=" md-form">
                            <label class="control-label">Title <span class="mecburi">*</span></label>
                            <input type="text" class="" name="title" id="title" value="{{old('title')}}" required >
                            
                        </div>
                        <div class="md-form" >
                            <label class="control-label">Content <span class="mecburi">*</span></label>
                            <textarea class="md-textarea" id="content" name="content" rows="10">{{old('content')}}</textarea>
                            <span class="material-input"></span>
                        </div>
                        <input type="file" name="img" id="img" class="form-control">
                        <input type="submit" name="submit" class="btn btn-info">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
