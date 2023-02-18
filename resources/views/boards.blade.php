@extends('layouts.main')
@section('title','Login')
@section('content')


@if(!empty($boardData))
    <div  class="d-flex justify-content-between p-3">
        <h3 class="text-secondary">Boards</h3>
        <a href="{{route('editBoard')}}"><button class="btn btn-outline-primary">Add Board</button></a>
    </div>
    @if(Session::has('success'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong></strong> {{Session::get('success')}}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
    @endif
    <div class="row p-3 d-flex ">
    @foreach($boardData as $board)
    <div class="card bg-light shadow-lg p-1 m-1 bg-white rounded" style="width: 18rem;" id="board_{{$board->id}}">
    <div class="card-body">
        <h5 class="card-title">{{$board->board_name}}</h5>
        <h6 class="card-subtitle mb-2 text-muted">{{$board->board_description}}</h6>
        <p class="card-text"></p>
        <a href="{{url('editBoard/'.$board->id)}}" class="card-link">Edit</a>
        <a href="{{url('task/'.$board->id)}}" class="card-link text-secondary">View</a>
        <a href="#" class="card-link text-danger delete-board" data-id="{{$board->id}}">Delete</a>
    </div>
    </div>
    @endforeach
    </div>
@endif


<script>
    $(document).ready(function(){
        $(document).on('click','.delete-board',function(e){
            boardId=$(this).attr('data-id');

            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this Board!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url:'{{route("deleteBoard")}}',
                        data:{_token:"{{csrf_token()}}",boardId:boardId},
                        method:'POST',
                        success:function(res){
                            if(res.status=='success'){
                                if(res.status=='success'){
                                    title='Success';
                                    icon="success";

                                    $('#board_'+boardId).remove();
                                }else{
                                    title='Danger';
                                    icon="warning";
                                }
                                swal({
                                    title: title,
                                    text: res.message,
                                    icon: icon,
                                });
                            }
                        }
                    });
                } 
            });            
        });
    });
</script>

@endsection


