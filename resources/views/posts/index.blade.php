@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <ul class="list-group">
        <center>
          <a href="{{url('posts/create')}}" class="btn btn-success ">Create + </a>
        </center>
        <br>
        
            @foreach($posts as $post)
            <li class="list-group-item">
                <span><img src="{{isset($post->thumb)?url('thumbs/'.$post->thumb):'http://lorempixel.com/40/30/'}}" width="40px" height="30px"></span> 
                <a href="{{url('posts')}}/{{$post->id}}">{{$post->title}}</a>
                
                <span class="pull-right">
                <a id="look" class="btn btn-md btn-info" href="{{url('posts')}}/{{$post->id}}"><i class="fa fa-eye" aria-hidden="true"></i></a>  
                @if(Auth::check()&&in_array($post->id,Auth::user()->posts()->pluck('id')->toArray()))
                <a id="edit" class="btn btn-md btn-success" href="{{url('posts')}}/{{$post->id}}/edit" post-id="{{$post->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></a> 
                <a class="btn btn-md btn-danger" href="{{url('posts')}}/{{$post->id}}" data-method="delete" 
                data-token="{{csrf_token()}}" data-confirm="Are you sure?"><i class="fa fa-times" aria-hidden="true"></i></a>  
                @endif
                </span>
            </li>
            @endforeach
        </ul>
        {{$posts->links()}}
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
                    <input type="hidden" name="postId" id="postId" >
                    <div class=" md-form">
                        <label class="control-label">Title <span class="mecburi">*</span></label>
                        <input type="text" class="" name="title" id="title" required >
                        
                    </div>
                    <div class="md-form" >
                        <label class="control-label">Content <span class="mecburi">*</span></label>
                        <textarea class="md-textarea" id="content" name="content" rows="10"></textarea>
                        <span class="material-input"></span>
                    </div>
                 
                    <input type="file" name="img" id="img" class="form-control">
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
              var id = $(this).attr('post-id');
              console.log(id);
              $.get('posts/'+id,function(data){
                  $('#title').val(data.title);
                  $('#content').val(data.content);
                  $('.control-label').addClass('active');
                  $('#postId').val(id);
                  $('#editForm').prop('action','{{url("posts")}}/'+id)

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