<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Scheme;
use App\Models\Customer;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EnrollmentController extends Controller
{
    public function index($user)
    {    
        $user = User::find($user);
        if(request()->status)
        {
            $enrollments = $user->enrollments->where('status',request()->status);
        }
        else{
            $enrollments = $user->enrollments;
        }
        // $enrollments = $user->enrollments;
        return json_encode($enrollments );
    }
    public function store()
    {
        $user_id = request()->user_id;
        $scheme_id = request()->scheme_id;
        $user = User::find ($user_id);
        $enrollment= $user->deposit($scheme_id) ;
        return json_encode($enrollment);
    }
    public function withdraw($enrollment)
    {
        $user_id = request()->user_id;
        $user = User::find ($user_id);
        $message=$user->withdraw($enrollment);
        return json_encode($message);
    }
    public function show($enrollment)
    {
        $enrollment= Enrollment::find($enrollment);
        return json_encode($enrollment);
    }
}
