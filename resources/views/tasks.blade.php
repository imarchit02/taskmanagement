@extends('layouts.main')
@section('title','Task')
@section('content')

<div class="d-flex justify-content-between p-3">
        <h3 class="text-secondary">{{ (!empty($result['boardData']->board_name))?$result['boardData']->board_name:'' }}</h3>
        <a href="{{url('editTask/'.$result['boardData']->id)}}"><button class="btn btn-outline-primary">Add Task</button></a>
</div>
@if(Session::has('success'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong></strong> {{Session::get('success')}}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
@endif
<div class="row p-3 d-flex justify-content-between">
    <div class="col-md-4 shadow p-3 bg-white rounded border border-primary" id="assignedTask">
        <p class="text-center text-primary">Assigned Tasks</p>
        @if(!empty($result['assignedTask']))
            @foreach($result['assignedTask'] as $task)
                <div class="toast mb-2 show" role="alert" aria-live="assertive" aria-atomic="true" id="task_{{$task->id}}">
                    <div class="toast-header">
                        <strong class="me-auto">{{$task->task_name}}</strong>
                        <a href="{{url('editTask/'.$result['boardData']->id.'/'.$task->id)}}" class="me-2"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="#" data-id="{{$task->id}}"  class="delete_task me-2"><i class="fa fa-trash"></i></a>
                        <select class="move_task" id="move_{{$task->id}}" data-id="{{$task->id}}">
                            <option value="1" selected>Assigned</option>
                            <option value="2">InProgress</option>
                            <option value="3">Completed</option>
                        </select>
                    </div>
                    <div class="toast-body">
                        {{$task->description}}
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div class="col-md-4 shadow p-3 bg-white rounded border border-info" id="progressTask">
        <p class="text-center text-info">InProgress Tasks</p>
        @if(!empty($result['inProgressTask']))
            @foreach($result['inProgressTask'] as $task)
                <div class="toast mb-2 show" role="alert" aria-live="assertive" aria-atomic="true" id="task_{{$task->id}}">
                    <div class="toast-header">
                        <strong class="me-auto">{{$task->task_name}}</strong>
                        <a href="{{url('editTask/'.$result['boardData']->id.'/'.$task->id)}}" class="me-2"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="#" data-id="{{$task->id}}"  class="delete_task me-2"><i class="fa fa-trash"></i></a>
                        <select class="move_task" id="move_{{$task->id}}" data-id="{{$task->id}}">
                            <option value="1">Assigned</option>
                            <option selected value="2">InProgress</option>
                            <option value="3">Completed</option>
                        </select>
                    </div>
                    <div class="toast-body">
                        {{$task->description}}
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div class="col-md-4  shadow p-3  bg-white rounded border border-success" id="completedTask">
        <p class="text-center text-success">Completed Tasks</p>
        @if(!empty($result['completedTask']))
            @foreach($result['completedTask'] as $task)
                <div class="toast mb-2 show" role="alert" aria-live="assertive" aria-atomic="true" id="task_{{$task->id}}">
                    <div class="toast-header">
                        <strong class="me-auto">{{$task->task_name}}</strong>
                        <a href="{{url('editTask/'.$result['boardData']->id.'/'.$task->id)}}" class="me-2"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="#" data-id="{{$task->id}}"  class="delete_task me-2"><i class="fa fa-trash"></i></a>
                        <select class="move_task" id="move_{{$task->id}}" data-id="{{$task->id}}">
                            <option value="1">Assigned</option>
                            <option value="2">InProgress</option>
                            <option selected value="3">Completed</option>
                        </select>
                    </div>
                    <div class="toast-body">
                        {{$task->description}}
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<script>
    $(document).ready(function(){
        $(document).on('change','.move_task',function(){
            val=$(this).val();
            taskId=$(this).attr('data-id');

            $.ajax({
                url:'{{route("changeStatus")}}',
                data:{_token:"{{csrf_token()}}",taskId:taskId,status:val},
                method:'POST',
                success:function(res){
                    if(res.status=='success'){
                        taskHtml=$('#task_'+taskId).clone();
                        $('#task_'+taskId).remove();

                        if(val==1){
                            $('#assignedTask').append(taskHtml);
                        }else if(val==2){
                            $('#progressTask').append(taskHtml);
                        }else if(val==3){
                            $('#completedTask').append(taskHtml);
                        }
                        
                        $("#move_"+taskId+" option[value="+val+"]").prop('selected', true);

                        swal({
                                title: 'Success',
                                text: res.message,
                                icon: 'success',
                        });
                    }
                }
            })

        });

        $(document).on('click','.delete_task',function(e){
            e.preventDefault();
            taskId=$(this).attr('data-id');
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this task!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url:'{{route("deleteTask")}}',
                        data:{_token:"{{csrf_token()}}",taskId:taskId},
                        method:'POST',
                        success:function(res){
                            if(res.status=='success'){
                                if(res.status=='success'){
                                    title='Success';
                                    icon="success";

                                    $('#task_'+taskId).remove();
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


