<?php

namespace PromptBuilder\controllers;

use PromptBuilder\core\Request;
use PromptBuilder\core\Response;

class AuthController {

    public function sendCode(Request $req, Response $res) {
        $body = $req->getBody();
        $phone = $body['phone'] ?? null;
        $clickId = $body['clickid'] ?? 'default-click-id';
        $userIP = $_SERVER['REMOTE_ADDR'];
        $userUA = $_SERVER['HTTP_USER_AGENT'];
        $evinaId = $body['evinaRequestId'] ?? null;
    
        if (!$phone || !$evinaId) {
            return $res->json(['success' => false, 'message' => 'Phone number and evinaRequestId are required'], 400);
        }
    
        $params = http_build_query([
            'clickid'   => $clickId,
            'msisdn'    => $phone,
            'method'    => 'sendOtp',
            'operator'  => 'etisalat',
            'pid'       => 1190,
            'offer_id'  => 15976,
            'userIP'    => $userIP,
            'userUA'    => $userUA,
            'data'      => $evinaId
        ]);
    
        $url = "http://fordragopro.com/papi/Serpkcae?$params";
    
        try {
            $response = file_get_contents($url);
            $decoded = json_decode($response, true);
    
            if ($decoded['response'] === 'success') {
                return $res->json([
                    'success' => true,
                    'sessionId' => $decoded['reqid'] ?? null,
                    'message' => 'OTP sent'
                ]);
            }
    
            return $res->json(['success' => false, 'message' => $decoded['message'] ?? 'Unknown error'], 400);
        } catch (\Exception $e) {
            return $res->json(['success' => false, 'message' => 'Request failed', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function verifyCode(Request $req, Response $res) {
        $body = $req->getBody();
        $phone = $body['phone'] ?? null;
        $code = $body['code'] ?? null;
        $sessionId = $body['sessionId'] ?? null;
        $clickId = $body['clickid'] ?? 'default-click-id';
        $userIP = $_SERVER['REMOTE_ADDR'];
        $userUA = $_SERVER['HTTP_USER_AGENT'];
        $evinaId = $body['evinaRequestId'] ?? null;
    
        if (!$phone || !$code || !$sessionId || !$evinaId) {
            return $res->json(['success' => false, 'message' => 'Missing verification data'], 400);
        }
    
        $params = http_build_query([
            'clickid'   => $clickId,
            'msisdn'    => $phone,
            'method'    => 'verifyOtp',
            'operator'  => 'etisalat',
            'otp'       => $code,
            'reqid'     => $sessionId,
            'pid'       => 1190,
            'offer_id'  => 15976,
            'userIP'    => $userIP,
            'userUA'    => $userUA,
            'data'      => $evinaId
        ]);
    
        $url = "http://fordragopro.com/papi/Serpkcae?$params";
    
        try {
            $response = file_get_contents($url);
            $decoded = json_decode($response, true);
    
            if ($decoded['response'] === 'success') {
                return $res->json(['success' => true, 'message' => 'OTP verified']);
            }
    
            return $res->json(['success' => false, 'message' => $decoded['message'] ?? 'Unknown error'], 400);
        } catch (\Exception $e) {
            return $res->json(['success' => false, 'message' => 'Verification failed', 'error' => $e->getMessage()], 500);
        }
    }
    
}

