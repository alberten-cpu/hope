<?php

namespace App\Http\Controllers\Venues;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Venues;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use File;

class VenueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param CustomerDataTable $dataTable
     * @return Application|Factory|View|JsonResponse
     */
    public function index()
    {
        return view('template.admin.venues.create_venues');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function apiIndex()
    {
        $venue = Venues::select('*')
            ->where('status', '=', 1)
            ->get();
        if ($venue){

            return response()->json([
                'status' => 200,
                'success' => true,
                'msg' => 'Data Found',
                'data' => $venue,


            ]);

        }
        else{

            return response()->json([
                'status' => 404,
                'success' => false,
                'data' => 'No Data Available',

            ]);

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function apistore(Request $request)
    {
        $this->validator($request->all())->validate();

        $venue = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_verified' => true,
            'is_active' => true,
            'role_id' => Role::CUSTOMER,

        ]);
        if ($venue){

            $venues = Venues::create([
                'user_id' => $venue->id,
                'name' => $venue->name,
                'email' => $venue->email,
                'password' => $venue->password,
                'description' => $request->description,
                'category' => $request->category,
                'place_name' => $request->placeName,
                'website' => $request->website,
                'phone_number' => $request->phoneNumber,
                'address' => $request->address,
                'image_path' => 'not uploaded',
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'opening_time' => $request->openingTime,
                'closing_time' => $request->closingTime,
                'coupon_code' => $request->couponCode,
                'status' => true,
            ]);
            if ($venues) {
                $path = public_path().'/venues/' . $venues->id;
                if (!file_exists($path)) {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }
                if (!$request->hasFile('fileName')) {
                    $basename = 'File not found , status 400';
                } else {
                    $filename   = uniqid() . "_" . time(); // 5dab1961e93a7_1571494241
                    $extension  = pathinfo( $_FILES["fileName"]["name"], PATHINFO_EXTENSION );
                    $basename   = $filename . '.' . $extension;
                    shell_exec('chmod -R 777'.$path.'/');
                    $path = public_path().'/venues/' . $venues->id.'/';
                    $destionation = $path . $basename;

                    /* move the file */
                    move_uploaded_file($_FILES["fileName"]["tmp_name"],$destionation);
                    $venue = Venues::find($venues->id);
                    $venue->image_path = $basename;
                    $venue->save();

                }

                return response()->json([
                    'status' => 200,
                    'success' => true,
                    'msg' => 'Venue created sucessfully',
                    'data' => $venue,
                    'fileName' => $basename

                ]);
            }else{
                return response()->json([
                    'status' => 409,
                    'success' => false,
                    'msg' => 'Unable to create',
                    'data' => $venue

                ]);

            }
        }else{

            return response()->json([
                'status' => 500,
                'success' => false,
                'msg' => 'Unable to create !! check again with other data',

            ]);

        }




    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {
        $venues = Venues::all();

        if ($venues) {

            return response()->json([
                'status' => 200,
                'success' => true,
                'msg' => 'Venue found',
                'data' => $venues

            ]);
        } else {

            return response()->json([
                'status' => 404,
                'success' => false,
                'msg' => 'Venue not found',

            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $venues = Venues::find($id);
        if ($venues) {

            return response()->json([
                'status' => 200,
                'success' => true,
                'msg' => 'Venue found',
                'data' => $venues

            ]);
        } else {

            return response()->json([
                'status' => 404,
                'success' => false,
                'data' => 'Venue not found',

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
        $venue = Venues::find($request->id);


        if ($venue) {

            $user = User::find($venue->user_id);

            if ($request->hasFile('fileName')) {
                $path = public_path().'/venues/' . $venue->id.'/'.$venue->image_path;
                if(file_exists($path)) {
                    unlink($path);
                }


                $filename   = uniqid() . "_" . time(); // 5dab1961e93a7_1571494241
                $extension  = pathinfo( $_FILES["fileName"]["name"], PATHINFO_EXTENSION );
                $basename   = $filename . '.' . $extension;
                $path = public_path().'/venues/' . $venue->id .'/';
                $destionation = $path . $basename;

                /* move the file */
                move_uploaded_file($_FILES["fileName"]["tmp_name"],$destionation);
            } else {

                $basename = $venue->image_path;
            }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            if ($user->isDirty()) {

                $user->save();
            }
            $venue->name = $user->name;
            $venue->email = $user->email;
            $venue->password = $user->password;
            $venue->category = $request->category;
            $venue->description = $request->description;
            $venue->place_name = $request->placeName;
            $venue->website = $request->website;
            $venue->phone_number = $request->phoneNumber;
            $venue->address = $request->address;
            $venue->image_path = $basename;
            $venue->latitude = $request->latitude;
            $venue->longitude = $request->longitude;
            $venue->opening_time = $request->openingTime;
            $venue->closing_time = $request->closingTime;
            $venue->coupon_code = $request->couponCode;

            if ($venue) {

                if (!$venue->isDirty()) {
                    return response()->json([
                        'status' => 200,
                        'success' => true,
                        'data' => 'info', 'No changes have made.'

                    ]);

                } else {

                    $venue->save();
                    return response()->json([
                        'status' => 200,
                        'success' => true,
                        'msg' => 'Updated Sucessfully',
                        'data' => $venue,
                        'fileName' => $basename

                    ]);
                }
            }
        }
        else{
            return response()->json([
                'status' => 404,
                'success' => false,
                'msg' => 'Venue not found',

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

/**
* Validator for validate data in the request.
*
* @param array $data The data
* @param int|null $id The identifier for update validation
*
* @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
**/
    protected function validator(array $data, int $id = null, int $venue_id = null)
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
                'password'  => ['required'],

            ]
        );
    }

}
