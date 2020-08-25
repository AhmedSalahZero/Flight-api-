<?php
namespace App\services\v1 ;
use App\Flight;
use Illuminate\Support\Collection;
use App\Airport; 
use Illuminate\Support\Facades\Validator;
use App\Http\Rules\StatusRule ; 

class flightServices
{
    
    private $clauseProperites = ['status','flightNumber'];

    private $rule = [
        'flightNumber'=>'required' , 
        'status'=>'required|flightStatus' ,
        'arrival.dateTime'=>'required|date'  , 
        'arrival.iataCode'=>'required' , 
        'departure.dateTime'=>'required|date'  , 
        'departure.iataCode'=>'required' , 
    ]; 


    public function validate($flight){

        $validator = Validator::make($flight, $this->rule);
        // get Error messages 
        // if($validator->fails())
        // {
        //     dd($validator->errors());


        // }
        // else{
        //     dd('everything is well ') ; 

        // }


        // (if) validator fails then redirect back
        $validator->validate(); // if error has happended then return with status code 422 
        // but if every thing is well continue executing the code 
        // dd('everything is good and there is no validator');






    }

    public function getFlights($parameters){
      

        if (empty($parameters))
        {
            return $this->filterFlights(Flight::all());
        }
        $withKeys = $this->getWithKeys($parameters);
        $whereClause = $this->getWhereClause($parameters);
    


        $flights = Flight::with($withKeys)->where($whereClause)->get();
        
       return $this->filterFlights($flights,$withKeys);
    }


    protected function filterFlights( $flights , $key=[]){
        $data = [] ;
        foreach ($flights as $flight)
        {
            $entry = [
                'flightNumber'=>$flight->flightNumber  ,
                'status'=>$flight->status,
                'href'=>Route('flights.show' , $flight->flightNumber)
            ];

            if (in_array('arrivalAirport' , $key))
            {
                $entry['arrival']=[
                    'dateTime'=>$flight->arrivalDateTime,
                    'iataCode'=>$flight->arrivalAirport->iataCode ,
                    'city'=>$flight->arrivalAirport->city ,
                    'province'=>$flight->arrivalAirport->province

                ];
            }
         if (in_array('departureAirport' , $key))
            {

                $entry['departure']=[
                    'dateTime'=>$flight->departureDataTime,
                    'iataCode'=>$flight->departureAirport-> iataCode,
                    'city'=>$flight->departureAirport->city ,
                    'province'=>$flight->departureAirport->province

                ];


            }

            $data[] = $entry ;
        }



        return $data ;
    }

    public function updateFlight( $request , $flightNumber){
        $newData = $request->all();
    

        $oldData = Flight::where('flightNumber' ,$flightNumber)->firstOrFail();
        $oldData->update([
            'flightNumber'=> $newData['flightNumber'] , 
            'arrivalDateTime'=>$newData['arrival']['dateTime'] ,
            'departureDataTime'=>$newData['departure']['dateTime'] ,
            'status'=>$newData['status']
        ]);
        return $this->filterFlights([ $oldData ]);
      
    }

    protected function getWithKeys($parameters){
        $withKeys = [];
        if (isset($parameters['include']))
        {

            $supportedInclude =
           [
                'arrivalAirport'=>'arrival' ,
                'departureAirport'=>'departure'
            ];
            $includeParas = explode(',',$parameters['include']);
            $includes = array_intersect($supportedInclude , $includeParas );
            //[array_intersect]:compares the values of two (or more) arrays, and returns the matches.
            $withKeys = array_keys($includes);
            //The array_keys() function returns an array containing the keys.
            // but presume that there is no intersection values  , so we define $withKey=[]
        }
        return $withKeys ;

    }

    protected function getWhereClause($parameters):array{
        $clause=[];

        foreach($this->clauseProperites as $pro)
        {
            if(in_array($pro , array_keys($parameters)))
            {
                $clause[$pro] = $parameters[$pro];

            }
        }

        return $clause ; 
    }

    public function createFlight($request){
        $arrivalAirportCode = $request->input('arrival.iataCode');
        $departureAirportCode = $request->input('departure.iataCode');
        $flightNumber = $request->input('flightNumber');
        $status=$request->input('status');
        // now we want to find the id for both of arrival and departue Airport ; 
        // then we go to ourData base to find that records  ; 
        $Airports = Airport::whereIn('iataCode',[$arrivalAirportCode , $departureAirportCode])->get();
       
        $iataCode = [] ; 
        foreach($Airports as $airport)
        {
            // find id and put it a value within a assoc array with key contains its iataCode  
            $iataCode[$airport->iataCode] = $airport->id ;
        }
        

        $newFlight = new Flight() ;
        $newFlight->flightNumber = $flightNumber ; 
        $newFlight->arrivalAirport_id = $iataCode[$arrivalAirportCode];
        $newFlight->departureAirport_id= $iataCode[$arrivalAirportCode];
        $newFlight->arrivalDateTime = $request->input('arrival.dateTime');
        $newFlight->departureDataTime = $request->input('departure.dateTime');
        $newFlight->status = $status ; 
        $newFlight->save();
    

         
        
       return $this->filterFlights([$newFlight]);


        


        // previous variable includes departure and arrival airport data from our database ;  

        

    }


}
