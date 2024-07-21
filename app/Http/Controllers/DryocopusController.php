<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordChangeRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DryocopusController extends Controller
{
    public function dashboard(Request $request): View
    {
        return view('dashboard');
    }

    public function login(Request $request): View|RedirectResponse
    {
        if ($request->isMethod('GET')) {
            return view('login');
        }

        $user_id = $request->post('id');
        $password = $request->post('pass');

        if (!Auth::attempt(['user_id' => $user_id, 'password' => $password])) {
            return redirect()
                ->route('login')
                ->with('auth.id', $user_id);
        }

        return redirect()->route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function security(Request $request): View
    {
        return view('user.security');
    }

    public function changePassword(PasswordChangeRequest $request): RedirectResponse
    {
        $password = $request->post('new_password');
        $user = $request->user();
        $user->password = $password;
        $user->save();

        return redirect()
            ->route('security')
            ->with('notify.success', __('messages.password.change.success'));
    }
}
