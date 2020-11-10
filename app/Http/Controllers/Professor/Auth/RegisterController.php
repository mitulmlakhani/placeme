<?php

namespace App\Http\Controllers\Professor\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Firestore;

class RegisterController extends Controller
{

    public function __construct(Firestore $firestore, Auth $auth)
    {
        $this->firestore = $firestore;
        $this->auth = $auth;
    }

    public function showForm()
    {
        return view('professor.auth.register');
    }

    public function register(RegisterRequest $request)
    {   
        try{
            $userProperties = [
                'displayName' => $request->first_name.' '.($request->middle_name ? ' '.$request->middle_name.' ' : '').$request->last_name,
                'email' => $request->email,
                'phoneNumber' => ($request->country_code[0] == '+' ? $request->country_code : "+".$request->country_code).$request->phone,
                'password' => $request->password,
                'disabled' => false
            ];
            
            $user = $this->auth->createUser($userProperties);

            $roleref = $this->firestore->database()->collection('user_role')->document($user->uid);
            $roleref->set([
                'role' => 'professor'
            ]);
            
            $detailsref = $this->firestore->database()->collection('user_details')->document($user->uid);
            $detailsref->set([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phoneNumber' => ($request->country_code[0] == '+' ? $request->country_code : "+".$request->country_code).$request->phone,
                'birthdate' => date('Y-m-d', strtotime($request->birthdate)),
                'role' => 'professor',
                'created_at' => time(),
            ]);
            
            $login = $this->auth->signInWithEmailAndPassword($request->email, $request->password);
            $token = $login->idToken();

            session(['auth' => [
                'id' => $user->uid,
                'token' => $token,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'birthdate' => date('Y-m-d', strtotime($request->birthdate)),
                'role' => 'professor',
            ]]);

            return redirect('/professor');

        } catch (\Exception $e) {
            return redirect()->back()->with('danger', $e->getMessage());
        }
        
    }
}