<?php

namespace App\Http\Middleware;

use Closure;
use Kreait\Firebase\Auth;

class FirebaseAuthenticated
{
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
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
        $path = 'student';

        if($request->is('professor') || $request->is('professor/*')) {
            $path = 'professor';
        }
        
        if($request->is('admin') || $request->is('admin/*')) {
            $path = 'admin';
        }

        if(session()->has('auth') == false) {
            
            if($path == 'professor') {
                return $this->redirectToProfessorLogin();
            }
            
            if($path == 'admin') {
                return $this->redirectToAdminLogin();
            }

            return $this->redirectToStudentLogin();
        }

        try {
            $this->auth->verifyIdToken(session('auth')['token']);
        } catch (\Throwable $th) {
            if($path == 'professor') {
                return $this->redirectToProfessorLogin();
            }
            
            if($path == 'admin') {
                return $this->redirectToAdminLogin();
            }

            return $this->redirectToStudentLogin();
        }
        
        if($path != 'student' && session('auth')['role'] == 'student') {
            return redirect('/');    
        }
        
        if($path != 'professor' && session('auth')['role'] == 'professor') {
            return redirect('/professor');    
        }
        
        if($path != 'admin' && session('auth')['role'] == 'admin') {
            return redirect('/admin');    
        }
        

        return $next($request);
    }

    public function redirectToStudentLogin()
    {
        return redirect('/login');
    }
    
    public function redirectToProfessorLogin()
    {
        return redirect('/professor/login');
    }
    
    public function redirectToAdminLogin()
    {
        return redirect('/admin/login');
    }
}
