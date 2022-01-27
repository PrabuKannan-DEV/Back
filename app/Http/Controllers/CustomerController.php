<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index ()
    {
        $customers = Customer::all();
        return json_encode($customers);
    }
    public function store()
    {
        $data=request()->data;
        $customer = Customer::create($data);
        return json_encode($customer);
    }
    public function show($customer)
    {
        $customer = Customer::find($customer);
       return json_encode($customer);
    }

    public function destroy()
    {
        $customer->delete();
        return redirect()->route('customers');
    }

    public function update()
    {
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone_number'=>'required|min:10|max:10',
        ]);
        $customer->update($data);
        return redirect('/customers/'.$customer->id);
    }
}
