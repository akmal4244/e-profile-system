<?php
// ============================================================
// includes/supabase.php — Supabase REST API Helper Class
// ============================================================
require_once __DIR__ . '/../config.php';

class Supabase {
    private $url;
    private $key;

    public function __construct($useServiceKey = false) {
        $this->url = SUPABASE_URL;
        $this->key = $useServiceKey ? SUPABASE_SERVICE_KEY : SUPABASE_ANON_KEY;
    }

    private function request($method, $endpoint, $data = null, $extraHeaders = []) {
        $ch = curl_init();
        $headers = array_merge([
            'Content-Type: application/json',
            'apikey: ' . $this->key,
            'Authorization: Bearer ' . $this->key,
            'Prefer: return=representation',
        ], $extraHeaders);

        curl_setopt_array($ch, [
            CURLOPT_URL => $this->url . '/rest/v1/' . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ['data' => json_decode($response, true), 'status' => $httpCode];
    }

    public function select($table, $query = '') {
        return $this->request('GET', $table . ($query ? '?' . $query : ''));
    }

    public function insert($table, $data) {
        return $this->request('POST', $table, $data);
    }

    public function update($table, $id, $data) {
        return $this->request('PATCH', $table . '?id=eq.' . $id, $data);
    }

    public function delete($table, $id) {
        return $this->request('DELETE', $table . '?id=eq.' . $id);
    }

    public function signIn($email, $password) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->url . '/auth/v1/token?grant_type=password',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode(['email' => $email, 'password' => $password]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'apikey: ' . $this->key,
            ],
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ['data' => json_decode($response, true), 'status' => $httpCode];
    }

    public function getPublicUrl($bucket, $path) {
        return $this->url . '/storage/v1/object/public/' . $bucket . '/' . $path;
    }
}