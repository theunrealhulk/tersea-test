<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\History;
use App\Models\Invitation;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function addAdmin(Request $request){
        if(! auth()->user()->isAdmin()) return response()->json(['message'=>'permission denied'],401);
        $data = $request->validate([
            'name'=>'required|string',
            'email'=>'required|string',
            'password'=>'required|string'
        ]);

        User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>bcrypt($data['password'])
        ]);
        return response()->json(['message'=>'addmin added successfully'],201);
    }

    public function getAdmins(){
        if(! auth()->user()->isAdmin()) return response()->json(['message'=>'permission denied for this action'],401);
        $admins= User::whereDoesntHave('employee')->get();
        return response()->json(['data'=>$admins],200);
    }

    public function addCompany(Request $request){
        if(! auth()->user()->isAdmin()) return response()->json(['message'=>'permission denied for this action'],401);
        $data = $request->validate([
            'name'=>'required|string',
            'website'=>'required|string',
            'about'=>'required|string'
        ]);

        Company::create([
            'name'=>$data['name'],
            'website'=>$data['website'],
            'about'=>bcrypt($data['about'])
        ]);
        return response()->json(['message'=>'Company added successfully'],201);
    }

    public function getCompanies(){
        if(! auth()->user()->isAdmin()) return response()->json(['message'=>'permission denied for this action'],401);
        return response()->json(['data'=>Company::with('employees.user')->get()],200);
    }

    public function updateCompany(Request $request,Company $company){
        if(! auth()->user()->isAdmin()) return response()->json(['message'=>'permission denied for this action'],401);
        $data = $request->validate([
            'name'=>'required|string',
            'website'=>'required|string',
            'about'=>'required|string'
        ]);

        $company->update([
            'name'=>$data['name'],
            'website'=>$data['website'],
            'about'=>bcrypt($data['about'])
        ]);
        return response()->json(['message'=>'Company updated successfully'],201);
    }

    public function destroyCompany(Company $company){
        if(! auth()->user()->isAdmin()) return response()->json(['message'=>'permission denied for this action'],401);

        if(count($company->employees)==0){

            $company->destroy($company->id);
            return response()->json(['message'=>'Company deleted successfully'],201);
        }

        return response()->json(['message'=>'cannot delete company with employees'],401);

    }

    public function invite(Request $request,Company $company){
        if(! auth()->user()->isAdmin()) return response()->json(['message'=>'permission denied for this action'],401);
        $data = $request->validate([
            'name'=>'required|string',
            'email'=>'required|unique:users,email,'
        ]);
        $preexisting_Invite=Invitation::where([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'company_id'=>$company->id,
            'user_id'=>auth()->user()->id
        ])->first();
        //if invitation has not already been sent
        if(!$preexisting_Invite)
        {
            //create invitation
            $invitation=Invitation::create([
                'name'=>$data['name'],
                'email'=>$data['email'],
                'user_id'=>auth()->user()->id,
                'company_id'=>$company->id
            ]);
            //create history
            History::create([
                'log'=> date('d/m/Y H:i', time())." / Admin ".auth()->user()->name . " sent invitation mail to ".$data['name']." to join Company : ".$company->name
            ]);
            //send email
            Mail::send('email', [
                'invitation'=>$invitation,
                'admin'=> auth()->user()->name,
                'company'=>$company->name
            ], function ($message) use($invitation,$company) {

                $message->to( $invitation->email )
                    ->subject("Invitation to join ".$company->name);
            });
            return response()->json(["message"=>"invitation mail sent!"],201);
        }
        //else send message beck stating invitation issued already
        return response()->json(["message"=>"invitation already issued!"],401);
    }

    public function cancel(Invitation $invitation){
        if(! auth()->user()->isAdmin()) return response()->json(['message'=>'permission denied for this action'],401);

        if($invitation->status == 'pending'){
            $invitation->destroy($invitation->id);
            //create history
            History::create([
                'log'=> date('d/m/Y H:i', time())." / Admin ".auth()->user()->name . " cancelled ".$invitation->name." invitation"
            ]);
            return response()->json(["message"=>"invitation cancelled"],201);
        }
        else
        return  response()->json(['message' => $invitation->name." already accepted the invite"]);
    }

    public function history()
    {
        return response()->json(['data'=> History::all()],200);
    }
}
