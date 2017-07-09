@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <ul class="list-group">
        @include('errors.errors')
        
        <center>
          <a href="{{url('users/create')}}" class="btn btn-success ">Create User + </a>
        </center>
      
        <br>
            
            @foreach($users as $user)
            <li class="list-group-item">
                
                <a href="{{url('users')}}/{{$user->id}}">{{$user->name}}</a>
                
                <span class="pull-right">
                <a id="look" class="btn btn-md btn-info" href="{{url('users')}}/{{$user->id}}"><i class="fa fa-eye" aria-hidden="true"></i></a>  
              
                <a id="edit" class="btn btn-md btn-success" href="{{url('users')}}/{{$user->id}}/edit" user-id="{{$user->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true" onclick="event.preventDefault()" ></i></a> 
                <a class="btn btn-md btn-danger" href="{{url('users')}}/{{$user->id}}" data-method="delete" 
                data-token="{{csrf_token()}}" data-confirm="Are you sure?"><i class="fa fa-times" aria-hidden="true"></i></a>  
          
                </span>
            </li>
            @endforeach
        </ul>
        {{$users->links()}}
        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
          <div class="modal-dialog">
          
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit</h4>
              </div>
              <div class="modal-body">
                <form id="editForm" action="" method="POST" enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    {{csrf_field()}}
                    <input type="hidden" name="userId" id="userId">
                    <div class=" md-form">
                        <label class="control-label">Name <span class="mecburi">*</span></label>
                        <input type="text" class="" name="name" id="name" value="{{old('name')}}" required >
                    </div>
                    <div class=" md-form">
                        <label class="control-label">Email <span class="mecburi">*</span></label>
                        <input type="text" class="" name="email" id="email" value="{{old('email')}}" required >
                    </div>
                    <small >if you don't want to change password, then just ignore it</small>
                     <div class=" md-form">
                        <label class="control-label">New password <span class="mecburi">*</span></label>
                        <input type="password" class="" name="password" id="password" >
                    </div>
                    <div class=" md-form">
                        <label class="control-label">New password again<span class="mecburi">*</span></label>
                        <input type="password" class="" name="password_confirmation" id="password_confirmation" >
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
              <div class="modal-footer">
                
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </div>
            
          </div>
        </div>
        <!-- /modal ends -->
    </div>
</div>
@endsection

@section('script')
    <script type="text/javascript">
        (function(){
            // $('#delete').click(function(e){
            //     e.preventDefault();
            //     var href = $(this).prop('href');
            //     $.post(href,{'_method':'delete'})
            //     .done(function(data){
            //         console.log(data);
            //     })
            //     .fail(function(xhr,status,err){
            //         console.log(xhr.responseText);
            //     });
            // });
            var laravel = {
            initialize: function() {
              this.methodLinks = $('a[data-method]');

              this.registerEvents();
            },

            registerEvents: function() {
              this.methodLinks.on('click', this.handleMethod);
            },

            handleMethod: function(e) {
              var link = $(this);
              var httpMethod = link.data('method').toUpperCase();
              var form;

              // If the data-method attribute is not PUT or DELETE,
              // then we don't know what to do. Just ignore.
              if ( $.inArray(httpMethod, ['PUT', 'DELETE']) === - 1 ) {
                return;
              }

              // Allow user to optionally provide data-confirm="Are you sure?"
              if ( link.data('confirm') ) {
                if ( ! laravel.verifyConfirm(link) ) {
                  return false;
                }
              }

              form = laravel.createForm(link);
              form.submit();

              e.preventDefault();
            },

            verifyConfirm: function(link) {
              return confirm(link.data('confirm'));
            },

            createForm: function(link) {
              var form = 
              $('<form>', {
                'method': 'POST',
                'action': link.attr('href')
              });

              var token = 
              $('<input>', {
                'type': 'hidden',
                'name': '_token',
                  'value': '<?php echo csrf_token(); ?>' // hmmmm...
                });

              var hiddenInput =
              $('<input>', {
                'name': '_method',
                'type': 'hidden',
                'value': link.data('method')
              });

              return form.append(token, hiddenInput)
                         .appendTo('body');
            }
          };

          laravel.initialize();
          $('#edit').click(function(e){
              e.preventDefault();
              $('#myModal').modal();
              var id = $(this).attr('user-id');
              $.get('users/'+id,function(data){
                  $('#name').val(data.name);
                  $('#email').val(data.email);
                  $('#role').val(data.role);
                  $('.control-label').addClass('active');
                  $('#userId').val(id);
                  $('#editForm').prop('action','{{url("users")}}/'+id);
              });
          });
          // $('#editForm').on('submit',function(e){
          //     e.preventDefault();
          //     var postId = $(this).attr('post-id');
          //     var data = {
          //       'title': $('#title').val(),
          //       'title': $('#title').val(),

          //     }
          //     $.ajax({
          //       url: '{{url("posts/")}}'+postId,
          //       method: 'PUT',
          //       data: data,
          //     })
          // });
        })();
    </script>
@endsection