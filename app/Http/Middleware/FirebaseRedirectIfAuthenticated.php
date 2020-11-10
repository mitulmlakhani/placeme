<?php

namespace App\Http\Middleware;

use Closure;
use Exception;

use Kreait\Firebase\Firestore;
use Kreait\Firebase\Auth;

class FirebaseRedirectIfAuthenticated
{

    public function __construct(Firestore $firestore, Auth $auth)
    {
        $this->auth = $auth;
        $this->firestore = $firestore;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(session()->has('auth') && isset(session('auth')['token'])) {
            try {
                $this->auth->verifyIdToken(session('auth')['token']);
                
                if(isset(session('auth')['role']) && session('auth')['role'] == 'professor') {
                    return redirect('/professor');
                }
                
                if(isset(session('auth')['role']) && session('auth')['role'] == 'admin') {
                    return redirect('/admin');
                }
                
                if(isset(session('auth')['role']) && session('auth')['role'] == 'student') {
                    return redirect('/');
                }
            } catch(Exception $e) {
            }
        }

        return $next($request);
    }
}
