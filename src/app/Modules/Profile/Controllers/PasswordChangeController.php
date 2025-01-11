<?php

namespace App\Modules\Profile\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordChangeController extends Controller
{
    public function show()
    {
        return view('Profile::password.change');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.current_password' => '現在のパスワードが正しくありません。',
            'password.confirmed' => '新しいパスワードと確認用パスワードが一致しません。',
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('profile.password.change')
            ->with('status', 'パスワードを変更しました。');
    }
} 