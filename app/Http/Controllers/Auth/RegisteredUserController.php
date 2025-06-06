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
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'jira_email' => 'required|string|email|max:255',
            'jira_api_token' => 'required|string',
        ]);

        // Authenticate Jira credentials first
        $authToken = base64_encode($request->jira_email . ':' . $request->jira_api_token);

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $authToken,
            'Accept' => 'application/json'
        ])->get('https://chiragahuja.atlassian.net/rest/api/3/myself');

        if (!$response->successful()) {
            return redirect(route('register', absolute: false))
                ->withErrors(['jira_error' => 'Invalid Jira credentials. Please check your email and API token.']);
        }

        // Jira validated: create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jira_email' => $request->jira_email,
            'jira_api_token' => Crypt::encryptString($request->jira_api_token),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

}
