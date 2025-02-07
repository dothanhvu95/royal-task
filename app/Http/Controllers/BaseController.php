<?php

namespace App\Http\Controllers;

use App\Constants\EndPoint;
use Illuminate\Support\Facades\Http;

class BaseController extends Controller
{
    protected $http;

    public function __construct()
    {
        $this->http = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]);
    }

    protected function withAuth()
    {
        return $this->http->withHeaders([
            'Authorization' => 'Bearer ' . session('access_token')
        ]);
    }
}