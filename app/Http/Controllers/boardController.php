<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\board;
use App\Models\task_board_mapping;

class boardController extends Controller
{

    public function dashboard(){
        return view('dashboard');
    }

    public function viewBoards(){
        $data=board::all();
        return view('boards',['boardData'=>$data]);
    }

    public function editBoard($id=0){
        $board_data=array();
        if($id!=0){
            $board_data=board::find($id);
        }
        return view('editBoard',['data'=>$board_data]);        
    }

    public function saveBoard(Request $req){
        $req->validate([
            'board_name'=>'required',
            'board_description'=>'required',
            'start_date'=>'required',
            'end_date'=>'required',
        ]);
        
        if($req->board_id!=0){
            //Update Operation
            $boardObj=board::find($req->board_id);
            $message='Board Update Successfully';
        }else{
            //Add Operation
            $boardObj=new board();
            $message='Board Added Successfully';
        }

        $boardObj->board_name=$req->board_name;
        $boardObj->board_description=$req->board_description;
        $boardObj->board_start_at=$req->start_date;
        $boardObj->board_end_at=$req->end_date;
        $boardObj->save();

        return redirect(route('boards'))->with('success',$message);
        
    }

    public function deleteBoard(Request $req){
        $obj=task_board_mapping::where('board_id','=',$req->boardId);
        if($obj){
            return response(['status'=>'fail','message'=>'Board Can not Delete, It link with some task']);
        }else{
            $boardObj=board::find($req->boardId);
            if($boardObj)
            {
                $boardObj->delete();
                return response(['status'=>'success','message'=>'Board Deleted Successfully']);
            }

            return response(['status'=>'fail','message'=>'Board Not Found']);
        }
    }
}
