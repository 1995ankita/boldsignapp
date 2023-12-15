<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function redirectToAuthorization()
    {
        $authorizationUrl = 'https://account.boldsign.com/connect/authorize';


        $params = [
            'response_type' => 'code',
            'client_id' => 'client_id',
            'state' => 'YTc2MzVhM2ItNGYyYjktY2EwYS00OTJkLTlmMjgtY2EwYzlhNzExNzE3',
            'scope' => 'BoldSign.Documents.All',
            'redirect_uri' => 'http://127.0.0.1:8000/list',
            'code_challenge' => '47DEQpj8HBSa-_TImW-5JCeuQeRkm5NMpJWZG3hSuFU',
            'code_challenge_method' => 'S256',
        ];

        return redirect()->away($authorizationUrl . '?' . http_build_query($params));
    }
    public function handleAuthorizationCallback(Request $request)
    {
        // Get the authorization code from the callback
        $code = $request->query('code');

        // Exchange the authorization code for an access token
        $response = Http::post('TOKEN_ENDPOINT_URL', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => 'client_id',
            'client_secret' => 'client_secret',
            'redirect_uri' => 'http://127.0.0.1:8000/list',
        ]);

        // Handle the response from the token endpoint
        if ($response->successful()) {
            $accessToken = $response->json()['access_token'];
            dd($response);
            // Handle storing the access token securely or using it for API requests
            // Redirect or perform further actions based on successful authorization
        } else {
            // Handle authorization failure
        }
    }
}
