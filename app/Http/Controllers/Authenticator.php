<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

session_start();

class Authenticator extends Controller
{
    private $client_id;
    private $client_secret;
    private $api_route;
    private $grant_type;
    
    public function __construct(){
        $this -> client_id = env('CLIENT_ID');
        $this -> client_secret = env('CLIENT_SECRET');
        $this -> api_route1 = env('AUTH_API_ROUTE1');
        $this -> api_route = env('AUTH_API_ROUTE');
        $this -> grant_type = env('GRANT_TYPE');
    }

    public function getAuthCode ()
    {
        $httpClient = new Client();
        
        $url = $this -> api_route1   . "/security/oauth2/token";
        // return $this -> api_route;

        $options = [
            'grant_type' => $this -> grant_type,
            'client_id' => $this -> client_id,
            'client_secret' => $this -> client_secret,
        ];

        try{
            $response = $httpClient -> post($url, [
                'header' => [
                    'Content-Type' => 'Application/json',
                ],
                'form_params' => $options
            ]);

            $res = json_decode($response -> getBody() -> getContents());
            // return $res;
            $res = $res -> access_token;

            
            $_SESSION['auth_code'] = $res;
            

            return $res;
        }
        catch(\Throwable $th){
            return $th;
        }
    }
    
    private function connect (){
        // https://test.api.amadeus.com/v1/security/oauth2/token?response_type=code&client_id=$clientId&redirect_uri=$redirectUri

        if(!isset($_SESSION['auth_code'])){
            $code = $this -> getAuthCode();
            return true;
        }
         
        // return $code;
        return $_SESSION['auth_code'];
        
    }

    public function flightOffers(Request $request){
        
        try{
            $connect = $this -> getAuthCode();
            if(empty($connect)){
                return response()->json([
                    'status' => 400,
                    'message' => 'Authentication failed'
                ], 200);
            }
            
            // proceed with api fetch for flight offers


            $client = new Client();

            $url = $this -> api_route. '/shopping/flight-offers';

            /*$options = [
                'countryCode' => 'NGN',
                'originDestinations' => [
                    [
                        'id' => '1',
                        'originLocationCode' => 'ABJ',
                        'destinationLocationCode' => 'NYC',
                        'departureDataTimeRange' => [
                            'date' => '2023-07-29',
                            'time' => '10:00:00'
                        ],
                    ]
                ],
                'destinationLocationCode' => 'NYC',
                'travelers' => [
                    [
                        'id' => '1',
                        'travelType' => 'ADULT'
                    ],
                ],
                // 'sources' => [
                //     'GDS',
                // ],
                // 'searchCriteria' => [
                //     'maxFlightOffers' => '2',
                //     'flightFilters' => [
                //         'cabinRestrictions' => [
                //             [
                //                 'cabin' => 'BUSINESS',
                //                 'coverage' => 'MOST_SEGMENTS',
                //                 'originDestinationIds' => [
                //                     '1'
                //                 ]
                //             ]
                //         ]
                //     ]
                // ]
            ];
            */

            $options = ' {
                "originDestinations": [
                    {
                        "id": "1",
                        "originLocationCode": "BCN",
                        "destinationLocationCode": "MAD",
                        "departureDateTimeRange": {
                            "date": "2023-07-09",
                            "time": "10:00:00"
                        },
                        "arrivalDateTimeRange":{
                        "date": "2023-07-10",
                        "time": "10:00:00" 
                        }
                    },
                            {
                        "id": "2",
                        "originLocationCode": "MAD",
                        "destinationLocationCode": "ATH",
                        "departureDateTimeRange": {
                            "date": "2023-07-11",
                            "time": "10:00:00"
                        }
                        ,
                        "arrivalDateTimeRange":{
                        "date": "2023-07-12",
                        "time": "10:00:00" 
                        }
                    }
                ],
                "travelers": [
                    {
                        "id": "1",
                        "travelerType": "ADULT",
                        "fareOptions": [
                            "STANDARD"
                        ]
                    },
                    {
                            "id": "2",
                        "travelerType": "HELD_INFANT",
                        "associatedAdultId": "1",
                        "fareOptions": [
                            "STANDARD"
                        ]
                    }
                ],
                    "sources": [
                    "GDS"
                    ]
                }
            ';

            $response = $client -> get($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $connect,
                    'Accept' => 'application/json'
                ],
                'form_params' => json_decode($options),
            ]);

            $client -> getBody() -> getContent();

            return response()->json([
                'status' => 200,
                'message' => $client
            ], 200);

        }
        catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle request errors here
            return $e->getMessage();
        }
    }
}
