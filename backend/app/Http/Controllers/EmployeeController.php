<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invitation;
class EmployeeController extends Controller
{
    public function accept(Invitation $invitation){
        if( auth()->user()->isAdmin()) return response()->json(['message'=>'permission denied for this action'],401);
        //confirm invitation exist
            // if yes update status to validated
            // update history
        //else send invitation has been cancelled
    }
    public function confirm(Request $request,Invitation $invitation){
        //confirm invitation exist
            // if yes update status to confirmed
            // update history
            // create new user
            // create associated employee
        //else send invitation has been cancelled
    }

}
