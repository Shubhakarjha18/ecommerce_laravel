<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Credentials required for authentication.
     */
    public function credentials(Request $request)
    {
        return [
            'email' => $request->email,
            'password' => $request->password,
            'status' => 'active',
        ];
    }

    /**
     * Handle post-authentication redirection based on role.
     */
    protected function authenticated(Request $request, $user)
    {
        // 1. Superadmin -> Admin Dashboard
        if ($user->hasRole('superadmin')) {
            return redirect()->route('admin');
        }

        // 2. Admin -> Admin Dashboard
        if ($user->hasRole('admin')) {
            return redirect()->route('admin');
        }

        // 3. Regular User -> User Dashboard
        if ($user->hasRole('user')) {
            return redirect()->route('user');
        }

        // Fallback for users without specific roles
        return redirect()->route('home');
    }

    /**
     * Redirect to the Socialite provider authentication page.
     */
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the Socialite callback after authentication.
     */
    public function Callback($provider)
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();

        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            Auth::login($user);

            // Redirect based on role after social login
            return $this->redirectBasedOnRole($user)->with('success', 'Logged in with ' . ucfirst($provider));
        }

        // Create a new user if one does not exist
        $user = User::create([
            'name' => $socialUser->getName(),
            'email' => $socialUser->getEmail(),
            'password' => bcrypt(str()->random(16)),
            'image' => $socialUser->getAvatar(),
            'provider_id' => $socialUser->getId(),
            'provider' => $provider,
            'status' => 'active',
        ]);

        // Assign default 'user' role
        $user->assignRole('user');

        Auth::login($user);

        return redirect()->route('user')->with('success', 'Account created via ' . ucfirst($provider));
    }

    /**
     * Helper method to redirect users based on role after Socialite login.
     */
    protected function redirectBasedOnRole($user)
    {
        if ($user->hasRole('superadmin') || $user->hasRole('admin')) {
            return redirect()->route('admin');
        }

        return redirect()->route('user');
    }
}
