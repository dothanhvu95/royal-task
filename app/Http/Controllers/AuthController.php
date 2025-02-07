<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Utils\EndPoint;

class AuthController extends BaseController
{
    public function showLoginForm()
    {
        if (session()->has('logged_in')) {
            return redirect()->route('profile');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        try {
            $response = $this->http->post(EndPoint::URL . '/api/v2/token', [
                'email' => $credentials['email'],
                'password' => $credentials['password']
            ]);
            
            if ($response->successful()) {
                // Lưu token vào session
                $data = $response->json();
                
                session([
                    'access_token' => $data['token_key'] ?? $data['refresh_token_key'],
                    'logged_in' => true,
                    'user' => $data['user'],
                    'last_login_at' => $data['last_active_date']
                ]);
    
                return redirect()->route('profile');
            }
    
            // Log response để debug
          
    
            return back()->withErrors([
                'email' => 'Incorrect login information please try again'
            ]);
    
        } catch (\Exception $e) {

            return back()->withErrors([
                'email' => 'Something went wrong please try again'
            ]);
        }
        
    }
    public function logout()
    {
        // Xóa tất cả session
        session()->flush();
        
        // Hoặc xóa từng session cụ thể
        // session()->forget(['logged_in', 'access_token']);
        
        return redirect()->route('signin');
    }
}
