<?php

namespace App\Http\Controllers;

use App\Models\Deals;
use App\Models\Venues;
use Illuminate\Http\Request;
Use File;

class DealsController extends Controller
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $deals = Deals::create([
            'venue_id' => $request->venueId,
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => 'not uploaded',
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'opening_time' => $request->openingTime,
            'closing_time' => $request->closingTime,
            'coupon_code' => $request->couponCode,
            'status' => true,
        ]);
        if ($deals) {
            $path = public_path() . '/deals/' . $deals->id;
            if (!file_exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            if (!$request->hasFile('fileName')) {
                $basename = 'File not found , status 400';
            } else {
                $filename = uniqid() . "_" . time(); // 5dab1961e93a7_1571494241
                $extension = pathinfo($_FILES["fileName"]["name"], PATHINFO_EXTENSION);
                $basename = $filename . '.' . $extension;
                shell_exec('chmod -R 777' . $path . '/');
                $path = public_path() . '/deals/' . $deals->id . '/';
                $destionation = $path . $basename;

                /* move the file */
                move_uploaded_file($_FILES["fileName"]["tmp_name"], $destionation);
                $deal = Deals::find($deals->id);
                $deal->image_path = $basename;
                $deal->save();

            }

            return response()->json([
                'status' => 200,
                'success' => true,
                'msg' => 'Deal created sucessfully',
                'data' => $deals,
                'fileName' => $basename

            ]);
        } else {
            return response()->json([
                'status' => 409,
                'success' => false,
                'msg' => 'Unable to create !!!',

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
    public function edit($id)
    {
        $deals = Deals::find($id);
        if ($deals) {

            return response()->json([
                'status' => 200,
                'success' => true,
                'msg' => 'Deal found',
                'data' => $deals

            ]);
        } else {

            return response()->json([
                'status' => 404,
                'success' => false,
                'data' => 'Deal not found',

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
    public function update(Request $request)
    {
        $deal = Deals::find($request->id);


        if ($deal) {

            if ($request->hasFile('fileName')) {
                $path = public_path().'/deals/' . $deal->id.'/'.$deal->image_path;
                if(file_exists($path)) {
                    $delete = unlink($path);
                }


                $filename   = uniqid() . "_" . time(); // 5dab1961e93a7_1571494241
                $extension  = pathinfo( $_FILES["fileName"]["name"], PATHINFO_EXTENSION );
                $basename   = $filename . '.' . $extension;
                $path = public_path().'/deals/' . $deal->id .'/';
                $destionation = $path . $basename;

                /* move the file */
                move_uploaded_file($_FILES["fileName"]["tmp_name"],$destionation);
            } else {

                $basename = $deal->image_path;
            }
            $deal->title = $request->title;
            $deal->description = $request->description;
            $deal->image_path = $basename;
            $deal->latitude = $request->latitude;
            $deal->longitude = $request->longitude;
            $deal->opening_time = $request->openingTime;
            $deal->closing_time = $request->closingTime;

            if (!$deal->isDirty()) {
                    return response()->json([
                        'status' => 200,
                        'success' => true,
                        'data' => 'info', 'No changes have made.'

                    ]);

                } else {

                $deal->save();
                    return response()->json([
                        'status' => 200,
                        'success' => true,
                        'msg' => 'Updated Sucessfully',
                        'data' => $deal,
                        'fileName' => $basename

                    ]);
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
}
