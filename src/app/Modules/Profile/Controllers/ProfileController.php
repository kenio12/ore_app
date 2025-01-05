<?php

namespace App\Modules\Profile\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        \Log::info('Profile index method called');
        
        try {
            return view('Profile::index');
        } catch (\Exception $e) {
            \Log::error('View error: ' . $e->getMessage());
            dd($e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
} 