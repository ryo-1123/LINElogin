<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Socialite;
class AuthController extends Controller
{
    /**
     * Redirect the user to the LINE authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('line')->redirect();
    }
    /**
     * Obtain the user information from LINE.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {

        try {
            $user = Socialite::driver('line')->user();
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
        if ($authUser = \App\User::where('mid', $user->id)->first()) {
            return $authUser;
        }
        return \App\User::create([
            'mid' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar
        ]);
    }
}