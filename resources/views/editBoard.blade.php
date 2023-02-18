@extends('layouts.main')
@section('title','Login')
@section('content')

<form action="{{route('saveBoard')}}" method="post" class="p-5" enctype="multipart/form-data">
    @csrf
    <h3 class="text-secondary pb-2">{{ (!empty($data)) ? 'Edit':'Add' }} Board</h3>
    <input type="hidden" name="board_id" value="{{ (!empty($data)) ? $data->id : 0 }}">
    <div class="form-group">
        <label for="board_name">Board Name</label>
        <input type="text" name="board_name" class="form-control" value="{{ (!empty($data->board_name)) ? $data->board_name : '' }}">
        @error('board_name')
        <small class="text-danger">{{$message}}</small>
        @enderror
    </div>
    <div class="form-group ">
        <label for="board_description">Desctiption</label>
        <textarea name="board_description" class="form-control" row="9" col="4" 
        value="{{ (!empty($data->board_description)) ? $data->board_description : '' }}">{{ (!empty($data->board_description)) ? $data->board_description : '' }}</textarea>
        @error('board_description')
        <small class="text-danger">{{$message}}</small>
        @enderror
    </div>
    <div class="row mb-4">
        <div class="col">
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ (!empty($data->board_start_at)) ? $data->board_start_at : '' }}">
            @error('start_date')
            <small class="text-danger">{{$message}}</small>
            @enderror
        </div>
        <div class="col">
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" class="form-control" value="{{ (!empty($data->board_end_at)) ? $data->board_end_at : '' }}">
            @error('end_date')
            <small class="text-danger">{{$message}}</small>
            @enderror
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

@endsection
