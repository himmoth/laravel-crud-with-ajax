<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    

    public function index(){

        return view('index');
    }

    public function store(Request $request){

       $file = $request->file('avatar');
       $fileName = time().'.'.$file->getClientOriginalExtension();
       $file->storeAs('public/images',$fileName);

       $empData = [
        'first_name' => $request->fname,
        'last_name' => $request->lname,
        'email' => $request->email,
        'phone' => $request->phone,
        'post' => $request->post,
        'avatar' => $fileName
       ];

       Employee::create($empData);

       return response()->json(['status' => 200]);


    }

    public function fetchAll(){

        $emps = Employee::latest()->get();
        $output ='';
        if($emps->count() > 0){
            $output .='<table class="table table-striped table-sm text-center align-middle"> 
                <thead>
                <tr>
                    <th> ID </th>
                    <th> Avatar </th>
                    <th> Name </th>
                    <th> Email </th>
                    <th> Post </th>
                    <th> Phone </th>
                    <th> Action </th>


                    
                </tr>

                </thead>
                <tbody>';

                $i =1;
                $path ="http://127.0.0.1:8000/";
                foreach($emps as $emp) {
                    $output .= '<tr>
                    
                    <td> '.$i++.'</td>
                    <td <img src="'.$path.'storage/images/'.$emp->avatar.'" width="30" class="img-thumbnail rounded-circle"> </td>
                    <td> '.$emp->first_name. ''. $emp->last_name .'</td>
                    <td> '.$emp->email.'</td>
                    <td> '.$emp->post.'</td>
                    <td> '.$emp->phone.'</td>
                    <td> 
                         <a href="#" id="'.$emp->id.'" class="text-success editIcon" data-bs-toggle="modal" data-bs-target="#editEmployeeModal"> <i class="fa-solid fa-pen-to-square"></i></a>
                        &nbsp;

                         <a href="#" id="'.$emp->id.'" class="text-danger deleteIcon" data-bs-toggle="modal" data-bs-target=""><i class="fa-solid fa-trash"></i></a>
                        
                    </td>


                    
                </tr>';
                }
                 
                $output .= '</tbody> </table>';
                echo $output;
        }else{
            echo '  <h1 class="text-center text-secondary my-5">No record</h1>';
          
        }
    }


    public function edit(Request $request){

        $id = $request->id;
        $emp = Employee::find($id);
        return response()->json($emp);
    }
}
