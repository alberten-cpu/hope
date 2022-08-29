<?php

namespace App\Http\Controllers\Admin\User;

use App\DataTables\Admin\User\CustomerDataTable;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use DataTables;
use File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Redirect;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param CustomerDataTable $dataTable
     * @return Application|Factory|View|JsonResponse
     */
    public function index(CustomerDataTable $dataTable)
    {
        return $dataTable->render('template.admin.user.customer.index_customer');
    }

    public function getCustomers()
    {
        if (\request()->ajax()) {
            $search = request()->search;
            $customers = User::with('customer:company_name,user_id')->select('id', 'name', 'role_id')->when(
                $search,
                function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                }
            )->where('role_id', Role::CUSTOMER)->limit(15)->get();
            $response = array();
            foreach ($customers as $customer) {
                $response[] = array(
                    "id" => $customer->id,
                    "text" => $customer->name . ',' . $customer->customer->company_name
                );
            }
            return response()->json($response);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validator($request->all())->validate();
        $request->has('is_active') ? $is_active = true : $is_active = false;
        //dd($request->all());
        $customer = User::create([
            'name' => $request->customer_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => $is_active,
            'role_id' => Role::CUSTOMER,
        ]);
        //$customer->customer_id = $customer->createIncrementCustomerId($customer->id);
        //$customer->save();
        if($customer) {
            $path = public_path().'/users/' . $customer->id;
            if (!file_exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
                shell_exec('chmod -R 777'.$path.'/');

            }
            $cust_id = $customer->id;
            $verification = 'https://amj-tech.co.nz/api/hope_new/public/api/customer_verify/'.$cust_id;
            $message = '';
            $message .= 'User Name:  '.$customer->email."\r\n";
            $message .= 'Password:  '.$request->password."\r\n";
            $message .= 'Kindly check the below link for the verification'."\r\n";
            $message .= $verification;
            mail($customer->email,"Account Verification",$message);
            return back()->with('sucess', 'created sucessfully');


        }
        else{
            return back()->with('fail', 'Unable to add Data');

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function apistore(Request $request)
    {
        $this->validator($request->all())->validate();
        $request->has('is_active') ? $is_active = true : $is_active = false;
        //dd($request->all());
        $customer = User::create([
            'name' => $request->customer_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => $is_active,
            'role_id' => Role::CUSTOMER,

        ]);

        if ($customer) {
            $path = public_path().'/users/' . $customer->id;
            if (!file_exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
                shell_exec('chmod -R 777'.$path.'/');

            }

            $cust_id = $customer->id;
            $verification = 'https://amj-tech.co.nz/api/hope_new/public/api/customer_verify/'.$cust_id;
            $message = '';
            $message .= 'User Name:  '.$customer->email."\r\n";
            $message .= 'Password:  '.$request->password."\r\n";
            $message .= 'Kindly check the below link for the verification'."\r\n";
            $message .= $verification;
            mail($customer->email,"Account Verification",$message);

            return response()->json([
                'status' => 200,
                'success' => true,
                'msg' => 'Customer created sucessfully',
                'data' => $customer

            ]);
        }else{
//            db2_rollback();
        }
    }

    /**
     * Validator for validate data in the request.
     *
     * @param array $data The data
     * @param int|null $id The identifier for update validation
     *
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     **/
    protected function validator(array $data, int $id = null, int $customer_id = null)
    {
        \Validator::extend(
            'without_spaces',
            function ($attr, $value) {
                return preg_match('/^\S*$/u', $value);
            }
        );


        return Validator::make(
            $data,
            [
                'customer_name' => ['required', 'string', 'max:250'],
                'email' => ['required', 'string', 'unique:users,email,' . $id],
                'password'  => ['required'],

            ]
        );
    }

    /**
     * Show the form for create dashboard a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('template.admin.user.customer.create_customer');
    }

    /**
     * Display the specified resource.
     *
     * @param User $customer
     * @return Response
     */
    public function show(User $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $customer
     * @return Application|Factory|View
     */
    public function edit(User $customer)
    {
        return view('template.admin.user.customer.edit_customer', compact('customer'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param User $customer
     * @return Application|Factory|View
     */
    public function apiedit($id)
    {
        $user = User::find($id);
        if ($user){

            return response()->json([
                'status' => 200,
                'success' => true,
                'msg' => 'customer found',
                'data' => $user

            ]);
        }else{

            return response()->json([
                'status' => 404,
                'success' => true,
                'msg' => 'customer not found',

            ]);

        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $customer
     * @return Application|Factory|View
     */
    public function verify($id)
    {
        $user = User::find($id);
        if ($user){
            $user->is_verified = true;
            $user->save();
            return Redirect::to('https://amj-tech.co.nz/api/hope_new/public/login');

        }else{

            return response()->json([
                'status' => 404,
                'success' => true,
                'msg' => 'customer not found',

            ]);

        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $customer
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, User $customer): RedirectResponse
    {
        $this->validator($request->all(), $customer->id, $customer->customer->id)->validate();
        $request->has('is_active') ? $is_active = true : $is_active = false;
        $customer->name = $request->first_name . ' ' . $request->last_name;
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->mobile = $request->mobile;
        $customer->is_active = $is_active;
        $customer_table = Customer::findOrFail($customer->customer->id);
        $customer_table->customer_id = $request->cid;
        $customer_table->area_id = $request->area_id;
        $customer_table->street_address_1 = $request->street_address_1;
        $customer_table->street_address_2 = $request->street_address_2;

        if (!$customer->isDirty()) {
            return back()->with('info', 'No changes have made.');
        }
        $customer->save();
        $customer_table->save();
        return back()->with('success', 'Customer details updated successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function apiupdate(Request $request)
    {
        $user = User::find($request->id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if ($user) {

            if (!$user->isDirty()) {
                return response()->json([
                    'status' => 200,
                    'success' => true,
                    'msg' => 'info', 'No changes have made.'

                ]);

            } else {

                $user->save();
                return response()->json([
                    'status' => 200,
                    'success' => true,
                    'msg' => 'Updated Sucessfully',
                    'data' => $user

                ]);
            }
        }
        else{
            return response()->json([
                'status' => 200,
                'success' => true,
                'msg' => 'user not found',

            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $customer
     * @return RedirectResponse
     */
    public function destroy(User $customer): RedirectResponse
    {
        $customer->delete();
        return back()->with('success', 'Customer details deleted successfully');
    }

    public function loginUser(Request $request)
    {

        $role = $request->role;
        $user = User::where('email', '=', $request->email)
            ->where('role_id', '=', $role)
            ->first();
        if($user) {

            if ($user->role_id == Role::CUSTOMER) {

                if ($user->is_verified == true) {

                    if (Hash::check($request->password, $user->password)) {
                        return response()->json([
                            'status' => 200,
                            'success' => true,
                            'msg' => 'Login Successfully',
                            'data' => $user

                        ]);
                    } else {
                        return response()->json([
                            'status' => 401,
                            'success' => true,
                            'msg' => 'password did not  matches'

                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 403,
                        'success' => true,
                        'msg' => 'account not verified !! , kindly check mail for activate Link !!'

                    ]);
                }
            }else{
                if (Hash::check($request->password, $user->password)) {
                    return response()->json([
                        'status' => 200,
                        'success' => true,
                        'msg' => 'Login Successfully',
                        'data' => $user

                    ]);
                } else {
                    return response()->json([
                        'status' => 401,
                        'success' => true,
                        'msg' => 'password did not  matches'

                    ]);
                }
            }
        }else{

            return response()->json([
                'status' => 404,
                'success' => true,
                'msg' => 'user not found !!'

            ]);
        }
    }


}
