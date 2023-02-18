@extends('layouts.main')
@section('title','Login')
@section('content')

<form action="{{route('saveTask')}}" method="post" class="p-5" enctype="multipart/form-data">
    @csrf
    <h3 class="text-secondary pb-2">{{ (!empty($data['taskData']->id)) ? 'Edit':'Add' }} Task</h3>
    <input type="hidden" name="task_id" value="{{ (!empty($data['taskData']->id)) ? $data['taskData']->id : 0 }}">
    <input type="hidden" name="board_id" value="{{ (!empty($data['boardId'])) ? $data['boardId'] : 0 }}">
    <div class="form-group mb-3">
        <label for="task_name">Task Name</label>
        <input type="text" name="task_name" class="form-control" value="{{ (!empty($data['taskData']->task_name)) ? $data['taskData']->task_name : '' }}">
        @error('task_name')
        <small class="text-danger">{{$message}}</small>
        @enderror
    </div>
    <div class="form-group mb-3 ">
        <label for="description">Task Desctiption</label>
        <textarea name="description" class="form-control" row="9" col="4" 
        value="{{ (!empty($data['taskData']->description)) ? $data['taskData']->description : '' }}">{{ (!empty($data['taskData']->description)) ? $data['taskData']->description : '' }}</textarea>
        @error('description')
        <small class="text-danger">{{$message}}</small>
        @enderror
    </div>
    <div class="row mb-4">
        <div class="col">
            <label for="task_start_date">Start Date</label>
            <input type="date" name="task_start_date" class="form-control" value="{{ (!empty($data['taskData']->task_start_date)) ? $data['taskData']->task_start_date : '' }}">
            @error('task_start_date')
            <small class="text-danger">{{$message}}</small>
            @enderror
        </div>
        <div class="col">
            <label for="task_end_date">End Date</label>
            <input type="date" name="task_end_date" class="form-control" value="{{ (!empty($data['taskData']->task_end_date)) ? $data['taskData']->task_end_date : '' }}">
            @error('task_end_date')
            <small class="text-danger">{{$message}}</small>
            @enderror
        </div>
    </div>
    <div class="form-group mb-3 ">
        <label for="status">Status</label>
        <select name="status" class="form-control">
            <option></option>
            <option value="1" {{ (!empty($data['taskData']->status) && $data['taskData']->status=='1') ? 'selected' : ''  }}>Accepted</option>
            <option value="2" {{ (!empty($data['taskData']->status) && $data['taskData']->status=='2') ? 'selected' : ''  }}>InProgress</option>
            <option value="3" {{ (!empty($data['taskData']->status) && $data['taskData']->status=='3') ? 'selected' : ''  }}>Completed</option>
        </select>
        @error('status')
        <small class="text-danger">{{$message}}</small>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

@endsection
