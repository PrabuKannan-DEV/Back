<?php
namespace App\Http\Controllers;

use App\Models\Scheme;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SchemeController extends Controller
{
    public function index ()
    {
        $schemes = Scheme::all();
        return json_encode($schemes);

    }
    public function store()
    {
        $data=request()->data;
        $data['interest']=$data['interest']/100;
        $scheme = Scheme::create($data);
        return json_encode($scheme);
    }
    public function show($scheme)
    {
        $scheme = Scheme::find($scheme);
       return json_encode($scheme);
    }

    public function destroy($scheme)
    {
        $scheme->delete();
        return redirect()->route('schemes');
    }

    public function update($scheme)
    {
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone_number'=>'required|min:10|max:10',
        ]);
        $customer->update($data);
        return redirect('/schemes/'.$customer->id);
    }
}
