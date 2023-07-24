<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invitation;
use App\Models\History;
use App\Models\User;
use App\Models\Employee;
class EmployeeController extends Controller
{
    public function accept( $id){
        //confirm invitation exist
        $invitation=Invitation::where('id',$id)->first();
        $invitation->load('company');
        if($invitation && $invitation->status==='pending'){
            // if yes update status to validated
            $invitation->update([
                'status' => 'valid'
            ]);
            $invitation->save();
            //create history
            History::create([
                'log'=> date('d/m/Y H:i', time())." / Employee ".$invitation->name . " validated his invitation"
            ]);

            return response()->json(['message'=>'invitation was validated','data'=>$invitation],201);
        }
        else if($invitation && $invitation->status==='valid')
        {
            return response()->json(['data'=>$invitation],201);
        }
        //else send invitation has been cancelled
        else{
            return response()->json(['message'=>'can\'t find any invitation to validate'],404);
        }
    }
    public function confirm(Request $request,$id){
        //confirm invitation exist
         $invitation=Invitation::where('id',$id)->first();

        if($invitation && $invitation->status==='valid'){
            // if yes update status to confirmed
            $data=$request->validate([
                'password' =>'required|string|min:6|',
                'email' => 'required|email',
                'name'=>'required|string'
            ]);
            // create new user
            $user=User::create([
                'email'=>$data['email'],
                'password'=>bcrypt($data['password']),
                'name'  =>$data['name']
            ]);

            // create associated employee
            Employee::create([
                'user_id'=>$user->id,
                'company_id'=>$invitation->company_id,
                'address'=>$request->address,
                'phone'=>$request->phone,
                'birth_day'=>$request->birth_day
            ]);
            // update history
            History::create([
                'log'=> date('d/m/Y H:i', time())." / Employee ".$invitation->name . " confirmed his profile"
            ]);

            $invitation->update([
                'status' => 'confirmed'
            ]);
            //return user token
            return response()->json([
                'message'=>'profile successful',
                'isAdmin'=>$user->isAdmin(),
                'token'=>$user->createToken('auth_token')->plainTextToken
            ],201);
        }
        //else send invitation has been cancelled
        else{
            return response()->json(['message'=>'can\'t find any invitation to confirm'],404);
        }
    }
    public function update(Request $request)
    {
        $data=$request->validate([
            'address'=>'required|string',
            'phone'=>'string|required',
            'birth_day'=>'date|nullable'
        ]);
        try{
            auth()->user()->employee->update($data);
            return response()->json(['message'=>'profile updated'],201);
        }
        catch(\Exception $e){
            return response()->json(['message'=>'could not update profile','exception'=>$e->getMessage()],401);
        }
    }
}
