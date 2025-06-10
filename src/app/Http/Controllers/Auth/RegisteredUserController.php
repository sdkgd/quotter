<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Quser;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Quser::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $quser=Quser::create([
            'user_name' => $request->name,
            'display_name'=> $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        event(new Registered($quser));

        Auth::login($quser);

        return redirect(route('dashboard', absolute: false));
    }
}
