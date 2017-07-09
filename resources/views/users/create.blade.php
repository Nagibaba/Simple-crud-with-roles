@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Create User</div>
                
                <div class="panel-body">
                    @include('errors.errors')
                    <form action="{{url('users')}}" method="POST" >
                        {{csrf_field()}}
                        <div class=" md-form">
                            <label class="control-label">Name <span class="mecburi">*</span></label>
                            <input type="text" class="" name="name" id="name" value="{{old('name')}}" required >
                        </div>

                        <div class=" md-form">
                            <label class="control-label">Email <span class="mecburi">*</span></label>
                            <input type="text" class="" name="email" id="email" value="{{old('email')}}" required >
                        </div>

                         <div class=" md-form">
                            <label class="control-label">Password <span class="mecburi">*</span></label>
                            <input type="password" class="" name="password" id="password"  required >
                        </div>
                        <div class=" md-form">
                            <label class="control-label">Password <span class="mecburi">*</span></label>
                            <input type="password" class="" name="password_confirmation" id="password_confirmation"  required >
                        </div>
                        
                        <div class=" md-select">
                            <label class="control-label">Role <span class="mecburi">*</span></label>
                            <select class="form-control" name="role" id="role" required>
                                <option value=""></option>
                                <option value="user">User</option>
                                <option value="editor">Editor</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                       
                        <input type="submit" name="submit" class="btn btn-info">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
