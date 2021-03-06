<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Kreait\Firebase\Firestore;

class HomeController extends Controller
{
    public function __construct(Firestore $firestore)
    {
        $this->firestore = $firestore;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function profile()
    {
        $detailsref = $this->firestore->database()->collection('user_details')->document(session('auth')['id'])->snapshot();
        return view('profile', ['detailsref' => $detailsref->data()]);
    }

    public function profileSave(ProfileRequest $request)
    {
        $detailsref = $this->firestore->database()->collection('user_details')->document(session('auth')['id']);
        $detailsref->set([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'birthdate' => date('Y-m-d', strtotime($request->birthdate)),
            'role' => 'student',
        ]);

        return redirect()->route('profile')->with('status', 'Profile updated successfully.');
    }
}
