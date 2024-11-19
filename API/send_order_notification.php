<?php

class Notification
{
    public function SendNotification($registration_ids, $title, $body, $notification_id)
    {
        $msg = "";

        if (!empty($notification_id) && !empty($title) && !empty($registration_ids)) {

            $keyFilePath = __DIR__ . "/service_account.json";

            $jsonKey = json_decode(file_get_contents($keyFilePath), true);
            $jwt = $this->createJwt($jsonKey);

            $accessToken = $this->getAccessToken($jwt);

            $fields = array(
                "message" => array(
                    "token" => $registration_ids[0],
                    "notification" => array(
                        "title" => $title,
                        "body" => $body
                    ),
                    "data" => array(
                        'redirect' => "notification",
                        'notification_id' => $notification_id
                    ),
                    "android" => array(
                        "priority" => "high"
                    )
                )
            );

            echo json_encode($fields, JSON_PRETTY_PRINT);
            echo $accessToken;
            exit;

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://fcm.googleapis.com/v1/projects/shop-maintenance/messages:send",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($fields),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer $accessToken",
                    "Content-Type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);

            echo json_encode($response, JSON_PRETTY_PRINT);
        }
        return $response;
    }

    private function createJwt($jsonKey)
    {
        $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $now = time();
        $expires = $now + 3600;
        $payload = json_encode([
            'iss' => $jsonKey['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $expires,
        ]);

        $base64UrlHeader = $this->base64UrlEncode($header);
        $base64UrlPayload = $this->base64UrlEncode($payload);

        $signatureInput = $base64UrlHeader . '.' . $base64UrlPayload;
        $signature = '';
        openssl_sign($signatureInput, $signature, $jsonKey['private_key'], 'sha256');

        $base64UrlSignature = $this->base64UrlEncode($signature);

        return $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;
    }

    private function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function getAccessToken($jwt)
    {
        $postFields = http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://oauth2.googleapis.com/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $responseData = json_decode($response, true);
        return $responseData['access_token'];
    }
}
