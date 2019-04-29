<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Contracts\Factory as Socialite;

class AuthController extends Controller
{
    /**
     * Redirect the user to the LINE authentication page.
     *
     * @return Response
     */

    protected $socialite;

    public function __construct(Socialite $socialite)
    {
        $this->socialite = $socialite;
    }
    
    public function redirectToProvider()
    {
        return $this->socialite->driver('line')->redirect();
    }
    /**
     * Obtain the user information from LINE.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {

        try {

            $user = $this->socialite->driver('line')->user();


         } catch (Exception $e) {   // \を入力することでnamespaceの縛りがなくなり例外処理ができる。
            return redirect()->intended('/');
         }
        $authUser = $this->findOrCreateUser($user);
        Auth::login($authUser, true);
        return redirect()->intended('dashboard');
    }
    /**
     * Logout
     *
     * @return Response
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->intended('/');
    }
    /**
     * Return user if exists; create and return if doesn't
     *
     * @param object $user
     * @return User
     */
    private function findOrCreateUser($user)
    {
        if ($authUser = \App\User::where('line_id', $user->line_id)->first()) {
            return $authUser;
        }
        return \App\User::create([
            'line_id' => $user->id,
            'user_name' => $user->name,
            'user_image' => $user->avatar
        ]);
    }
}