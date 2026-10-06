<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'min:2', 'max:60'],
            'username' => ['required', 'alpha_dash', 'min:3', 'max:20', 'unique:users,username'],
            'email'    => ['required', 'email:rfc', 'max:120', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()],
            'terms'    => ['accepted'],
        ], [
            'username.alpha_dash' => 'Use only letters, numbers, dashes and underscores.',
            'username.unique'     => 'That username is taken. Try another one.',
            'email.unique'        => 'An account with this email already exists. Try logging in.',
            'email.email'         => 'Enter a valid email address, like name@example.com.',
            'password.confirmed'  => 'Passwords do not match.',
            'terms.accepted'      => 'Please accept the terms to create your account.',
        ]);

        $user = User::create([
            'name' => $data['name'], 'username' => strtolower($data['username']),
            'email' => strtolower($data['email']), 'password' => $data['password'], // hashed by model cast
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('collection')->with('success', "Welcome to Lavéa, {$user->name}!");
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ], ['login.required' => 'Enter your email or username.', 'password.required' => 'Enter your password.']);

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$field => strtolower($request->login), 'password' => $request->password], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('collection'))->with('success', 'Welcome back!');
        }

        return back()->withInput($request->only('login'))
            ->withErrors(['login' => 'We could not find an account with those details. Check them and try again.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'You have been logged out.');
    }
}
