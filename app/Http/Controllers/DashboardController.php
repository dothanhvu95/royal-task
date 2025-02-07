<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Utils\EndPoint;

class DashboardController extends BaseController
{
    public function index()
    {
        try {
            $userId = session('user.id');
            $title = 'Profile';
            $response = $this->withAuth()->get(EndPoint::URL . "/api/v2/users/{$userId}");

            if ($response->successful()) {
                $user = $response->json();
                return view('layouts.profile', compact('user', 'title'));
            }

            return back()->with('error', 'Failed to fetch profile');

        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while fetching profile');
        }
    }
}
