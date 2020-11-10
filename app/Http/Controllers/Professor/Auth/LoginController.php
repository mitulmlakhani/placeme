<?php

namespace App\Http\Controllers\Professor\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Firestore;

class LoginController extends Controller
{

    public function __construct(Firestore $firestore, Auth $auth)
    {
        $this->firestore = $firestore;
        $this->auth = $auth;
    }

    public function showForm()
    {
        return view('professor.auth.login');
    }

    public function login(LoginRequest $request)
    {
        try{

            $signInResult = $this->auth->signInWithEmailAndPassword($request->email, $request->password);
            $token = $signInResult->idToken();

            $user_role = $this->firestore->database()->collection('user_role')->document($signInResult->data()['localId'])->snapshot()->data();
            $user_details = $this->firestore->database()->collection('user_details')->document($signInResult->data()['localId'])->snapshot()->data();
            
            if(isset($user_role) && $user_role['role'] != 'professor') {
                return redirect()->back()->with('danger', 'Invalid username and password.');
            }

            session(['auth' => [
                'id' => $signInResult->data()['localId'],
                'token' => $token,
                'first_name' => $user_details['first_name'],
                'middle_name' => $user_details['middle_name'],
                'last_name' => $user_details['last_name'],
                'birthdate' => $user_details['birthdate'],
                'role' => 'professor',
                'created_at' => time(),
            ]]);

            return redirect('/professor');

        } catch (\Exception $e) {
            return redirect()->back()->with('danger', $e->getMessage());
        }
        
    }

    public function logout(Request $request) {
        $request->session()->flush();
        return redirect('professor');
    }
}