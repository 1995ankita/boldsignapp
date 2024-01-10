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
            'client_id' => 'bc45f496-8ebd-4c7c-b01b-4927ee5cd9b4',
            'state' => 'YTc2MzVhM2ItNGYyYjktY2EwYS00OTJkLTlmMjgtY2EwYzlhNzExNzE3',
            'scope' => 'BoldSign.Documents.All',
            'redirect_uri' => 'http://127.0.0.1:8000/list',
            'code_challenge' => '47DEQpj8HBSa-_TImW-5JCeuQeRkm5NMpJWZG3hSuFU',
            'code_challenge_method' => 'S256',
        ];

        return redirect()->away($authorizationUrl . '?' . http_build_query($params));
    }

    public function getAccessToken(Request $request)
    {
        $authCode = $request->query('code');
        $tokenEndpoint = 'https://account.boldsign.com/connect/token';

        $params = [
            'grant_type' => 'authorization_code',
            'client_id' => 'bc45f496-8ebd-4c7c-b01b-4927ee5cd9b4',
            'client_secret' => '3b5749f8-2273-4d05-b328-d6aae6e6454f',
            'code' => $authCode,
            'redirect_uri' => 'http://127.0.0.1:8000/list',
            'code_verifier' => 'nxHHp32nZDub7D8IHEuqHhRzdjdklnFc-G0zY3nNeU',
        ];

        $response = Http::asForm()->post($tokenEndpoint, $params);

        if ($response->successful()) {
            $accessToken = $response->json();
            return $accessToken;
        } else {
            $error = $response->json();
            return $error;
        }
    }
}

