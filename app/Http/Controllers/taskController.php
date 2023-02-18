<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\board;
use App\Models\task;
use App\Models\task_board_mapping;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class taskController extends Controller
{
    public function viewTasks($boardId){
        $result=array();
        if(!empty($boardId)){
            $result=array();
            
            $boardData=board::find($boardId);
            $result['boardData']=$boardData;

            //Fetch All the task of board and of logined User
            $taskData=task_board_mapping::
            join('task as tk','tk.id','=','task_board_mapping.task_id')
            ->where('task_board_mapping.board_id','=',$boardId)
            ->where('task_board_mapping.user_id','=',Auth::user()->id)
            ->select('tk.*')
            ->get();


            if(!empty($taskData)){
                $assignedTask=new collection();
                $inProgressTask=new collection();
                $completedTask=new collection();

                foreach($taskData as $data){
                    if($data->status==1){
                        $assignedTask->push($data);
                    }else if($data->status==2){
                        $inProgressTask->push($data);
                    }else if($data->status==3){
                        $completedTask->push($data);
                    }
                }

                $result['assignedTask']=$assignedTask;
                $result['inProgressTask']=$inProgressTask;
                $result['completedTask']=$completedTask;
            }   
        }

        return view('tasks',['result'=>$result]);
    }

    public function editTask($boardId=0,$taskId=0){
        $result=array();
        $result['boardId']=$boardId;
        $result['taskData']= ($taskId!=0)?task::find($taskId):'';

        return view('editTask',['data'=>$result]);        
    }

    public function saveTask(Request $req){
        $req->validate([
            'task_name'=>'required',
            'description'=>'required',
            'task_start_date'=>'required',
            'task_end_date'=>'required',
            'status'=>'required',
        ]);
        
        if($req->task_id!=0){
            //Update Operation
            $taskObj=task::find($req->task_id);
            $message='Task Update Successfully';
        }else{
            //Add Operation
            $taskObj=new task();
            $message='Task Added Successfully';
        }

        $taskObj->task_name=$req->task_name;
        $taskObj->description=$req->description;
        $taskObj->user_id=Auth::user()->id;
        $taskObj->task_start_date=$req->task_start_date;
        $taskObj->task_end_date=$req->task_end_date;
        $taskObj->status=$req->status;
        $taskObj->save();

        if($req->taskId==0){
           $mapObj=new task_board_mapping();
           $mapObj->user_id=Auth::user()->id;
           $mapObj->task_id=$taskObj->id;
           $mapObj->board_id=$req->board_id;
           $mapObj->status=$req->status;
           $mapObj->save();
        }

        return redirect(url('task/'.$req->board_id))->with('success',$message);
        
    }

    public function deleteTask(Request $req){
        $taskObj=task::find($req->taskId);
        if($taskObj)
        {
            $taskObj->delete();
            task_board_mapping::where('task_id','=',$req->taskId)->delete();
            return response(['status'=>'success','message'=>'Task Deleted Successfully']);
        }

        return response(['status'=>'fail','message'=>'Something Went Wrong']);
    }

    public function changeStatus(Request $req){
        if(!empty($req->taskId)){
           $taskObj=task::find($req->taskId);
           $taskObj->status=$req->status;
           $taskObj->save();

           return response(['status'=>'success','message'=>'status changed successfully'],200);
        }
        return response(['status'=>'fail','message'=>'Something Went Wrong'],401);
    }
}

