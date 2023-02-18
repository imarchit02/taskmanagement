<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\board;
use App\Models\task;
use App\Models\task_board_mapping;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class apiController extends Controller
{
    
    //Registration Function

        function RegisterUser(Request $req){

            $req->validate([
                'username'=>'required',
                'email'=>'required|unique:users',
                'password'=>'required|confirmed',
                'password_confirmation'=>'required',
            ]);

            $usrObj=User::create([
                'name'=>$req->username,
                'email'=>$req->email,
                'password'=>Hash::make($req->password),
            ]);

            $token=$usrObj->createToken('myToken')->plainTextToken;

            return response([
                'user'=>$usrObj,
                'token'=>$token,
            ],200);
        }

        public function loginUser(Request $req){
            $req->validate([
                'email'=>'required',
                'password'=>'required',
            ]);

            $userObj=user::where('email',$req->email)->first();


            if(!$userObj || Hash::check($req->password,$userObj->password)){
                return response([
                    'message'=>'The provided credential are incorrect',
                ],401);            
            }

            $token=$userObj->createToken('myToken')->plainTextToken;

            return response([
                'user'=>$userObj,
                'token'=>$token,
            ],200);

        }

        public function logout(user $user){
            $user->tokens()->delete();
            return response()->json(['message'=>'Successfully Logged Out']);
        }

    /***** */

    //Board CRUD Functions
        public function fetchBoardData($id=0){

            if($id!=0)
            {
                $boardData=board::find($id);
                return response([
                    'message'=>'Success',
                    'data'=>$boardData,
                ],200);
            }else{
                return response([
                    'message'=>'Board Name Does Not Exist',
                ],401);            
            }

        }

        public function InsertBoardData(Request $req){
            $req->validate([
                'board_name'=>'required',
                'board_description'=>'required',
                'start_date'=>'required|date_format:Y-m-d',
                'end_date'=>'required|date_format:Y-m-d',
            ]);

            $boardObj=new board();
            $boardObj->board_name=$req->board_name;
            $boardObj->board_description=$req->board_description;
            $boardObj->board_start_at=$req->start_date;
            $boardObj->board_end_at=$req->end_date;
            $boardObj->save();

            return response([
                'message'=>'Board Added Successfully',
            ],200);            
        }

        public function UpdateBoardData(Request $req,$boardId){

            $boardObj=board::find($boardId);
            $boardObj->update($req->all()); 

            return response([
                'message'=>'Board Updated Successfully',
            ],200);            

        }

        public function deleteBoardData($id){
            $obj=task_board_mapping::where('board_id','=',$id);
            if($obj){
                return response(['message'=>'Board Can not Delete, It link with some task'],401);
            }else{
                $boardObj=board::find($id);
                if($boardObj)
                {
                    $boardObj->delete();
                    return response(['message'=>'Board Deleted Successfully'],200);
                }

                return response(['message'=>'Board Not Found'],401);
            }
        }   
    //************** */

    //Task CRUD Function
        public function fetchTaskData($id=0){

            if($id!=0)
            {
                $taskData=task::find($id);
                return response([
                    'message'=>'Success',
                    'data'=>$taskData,
                ],200);
            }else{
                return response([
                    'message'=>'Task Does Not Exist',
                ],401);            
            }

        }

        public function InsertTaskData(Request $req){


            $req->validate([
                'task_name'=>'required',
                'description'=>'required',
                'task_start_date'=>'required',
                'task_end_date'=>'required',
                'status'=>('required|integer'),
                'status'=>Rule::in([1,2,3]),
                'board_id'=>'required|integer',
            ]);

            $id=board::find($req->board_id);

            if($id){
                $taskObj=new task();
                $taskObj->task_name=$req->task_name;
                $taskObj->description=$req->description;
                $taskObj->task_start_date=$req->task_start_date;
                $taskObj->task_end_date=$req->task_end_date;
                $taskObj->status=$req->status;
                $taskObj->user_id=Auth::user()->id;
                $taskObj->save();

                $mapObj=new task_board_mapping();
                $mapObj->user_id=Auth::user()->id;
                $mapObj->task_id=$taskObj->id;
                $mapObj->board_id=$req->board_id;
                $mapObj->status=$req->status;
                $mapObj->save();

                return response([
                    'message'=>'Task Added Successfully',
                ],200);            
            }else{
                return response([
                    'message'=>'Board Not Found',
                ],401);            
            }

        }

        public function UpdateTaskData(Request $req,$taskId){

            $taskObj=task::find($taskId);
            $taskObj->update($req->all()); 

            return response([
                'message'=>'Task Updated Successfully',
            ],200);            

        }

        public function deleteTaskData($id){
            $taskObj=task::find($id);
            if($taskObj)
            {
                $taskObj->delete();
                task_board_mapping::where('task_id','=',$id)->delete();
                return response(['message'=>'Task Deleted Successfully'],200);
            }

            return response(['message'=>'Task Not Found'],401);

        }
    /************** */
}
