<?php

namespace App\Http\Controllers\Enduser;

use App\DataTables\Admin\User\CustomerDataTable;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\Venues;
use App\Models\Deals;
use Illuminate\Support\Facades\Session;
use DataTables;
use File;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
Use \Carbon\Carbon;
use Illuminate\Validation\ValidationException;


class EndUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewVenue()
    {
        $venues = Venues::get();

        if($venues){

            return response()->json([
                'status' => 200,
                'success' => true,
                'msg' => 'Venues Found',
                'data' => $venues,

            ]);
        } else {
            return response()->json([
                'status' => 404,
                'success' => false,
                'msg' => 'Unable to Find !!!',

            ]);

        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewDeals()
    {
        //$deals = Deals::get();
        $deals = Deals::join('venues', 'venues.id', '=', 'deals.venue_id')
            ->get(['deals.*', 'venues.name','venues.category','venues.place_name']);

        if($deals){

            return response()->json([
                'status' => 200,
                'success' => true,
                'msg' => 'Deals Found',
                'data' => $deals,

            ]);
        } else {
            return response()->json([
                'status' => 409,
                'success' => false,
                'msg' => 'Unable to find !!!',

            ]);

        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewVenueDeals(Request $request)
    {

        $venues = Venues::where('id', $request->id)->get();
        

        if($venues){
            $deals = Deals::where('venue_id', $request->id)->get();
            return response()->json([
                'status' => 200,
                'success' => true,
                'msg' => 'Data Found',
                'venueData' => $venues,
                'dealsData' => $deals,

            ]);
        } else {
            return response()->json([
                'status' => 409,
                'success' => false,
                'msg' => 'Unable to find !!!',

            ]);

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
                'name' => ['required', 'string', 'max:250'],
                'email' => ['required', 'string', 'unique:users,email,' . $id],
                'date_of_birth'  => ['required'],
                'password'  => ['required'],

            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $date = Carbon::now();
        //dd($date);
        $this->validator($request->all())->validate();
        $end_user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth,
            'email_verified_at' => $date,
            'password' => Hash::make($request->password),
            'role_id' => Role::ENDUSER,
        ]);
        if($end_user){
            $path = public_path().'/users/' . $end_user->id;
            if (!file_exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }

            Mail::send('template.admin.email.customer_create', $end_user->toArray(),
                function($message){

                    $message->to('alberteb2910@gmail.com','code')->subject('User create Sucessfully');
                });

            return response()->json([
                'status' => 200,
                'success' => true,
                'msg' => 'user created sucessfully',
                'data' =>$end_user

            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function apiedit($id)
    {
        $user = User::find($id);
        if ($user){

            return response()->json([
                'status' => 200,
                'success' => true,
                'msg' => 'user found',
                'data' => $user

            ]);
        }else{

            return response()->json([
                'status' => 404,
                'success' => false,
                'msg' => 'user not found',

            ]);

        }

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
        $user->date_of_birth = $request->date_of_birth;
        $user->password = Hash::make($request->password);

        if (!$user->isDirty()) {
            return response()->json([
                'status' => 200,
                'success' => true,
                'msg' => 'info', 'No changes have made.'

            ]);

        }
        else{

            $user->save();
            return response()->json([
                'status' => 200,
                'success' => true,
                'msg' => 'Updated Sucessfully',
                'data' => $user

            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
