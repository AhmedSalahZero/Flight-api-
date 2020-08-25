<?php

namespace App\Http\Controllers\v1;

use App\Customer;
use App\Flight;
use App\Http\Controllers\Controller;
use App\services\v1\flightServices ;
use App\User;
use Illuminate\Http\Request;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException; 
use App\services\v1\class2 ; 

class FlightController extends Controller
{
    protected $flights ;
    public function __construct(flightServices $service)
    {
        $this->flights = $service ;
        $this->middleware('auth:api' , ['only'=>'store' , 'update' , 'destroy']);
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()

    {
        $parameters = Request()->input();
        $data = $this->flights->getFlights($parameters);
         return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->flights->validate($request->all());
      try{

        $flight = $this->flights->createFlight($request) ; 
        return response()->json($flight,201);
      }
      catch(Exception $e)
      {
          return response()->json([
              'message'=>$e->getMessage()
           ] , 500) ;   
           

    }
}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($flightNumber)
    {
       
        $parameters = Request()->input();
        $parameters['flightNumber'] = $flightNumber ; 
        $data =  $this->flights->getFlights($parameters);

       return response()->json($data);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $oldFlightNumber)
    {
        $this->flights->validate($request->all());

       try{
        $data = $this->flights->updateFlight($request,$oldFlightNumber);
        return response()->json($data,200);
       }
       catch(ModelNotFoundException $x){
           throw $x ; 

           
       }
       catch(Exception  $e){
        return response()->json([
            'message'=>$e->getMessage()
         ] , 500) ;   
       }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($flightNumber)
    {
        try{
            DB::delete('delete from flights where flightNumber = ?', [$flightNumber]);
            return response()->make('',204);

           }
           catch(ModelNotFoundException $x){
               throw $x ; 
    
               
           }
           catch(Exception  $e){
            return response()->json([
                'message'=>$e->getMessage()
             ] , 500) ;   
           }

        
  

        
    }
}
