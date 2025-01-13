<?php

namespace App\Modules\Profile\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\App\Models\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function index()
    {
        Log::info('Profile index method called');
        
        try {
            $apps = App::where('user_id', auth()->id())
                       ->latest()
                       ->get();

            $statusLabels = [
                'development' => '開発中',
                'released' => 'リリース済み',
                'maintenance' => 'メンテナンス中',
            ];

            return view('Profile::index', [
                'apps' => $apps,
                'statusLabels' => $statusLabels,
            ]);
        } catch (\Exception $e) {
            Log::error('View error: ' . $e->getMessage());
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