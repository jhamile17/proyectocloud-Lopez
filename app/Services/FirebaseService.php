<?php

namespace App\Services;

use Google\Client;

class FirebaseService
{
    public function getAccessToken()
    {
        $client = new Client();

        $client->setAuthConfig(storage_path('app/firebase/firebase.json'));

        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $client->useApplicationDefaultCredentials();

        $token = $client->fetchAccessTokenWithAssertion();

        if (isset($token['error'])) {
            throw new \Exception('Error al obtener token de Firebase: ' . $token['error']);
        }

        return $token['access_token'];
    }
}