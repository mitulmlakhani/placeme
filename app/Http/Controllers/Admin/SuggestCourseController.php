<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Firestore;

class SuggestCourseController extends Controller
{
    public function __construct(Firestore $firestore)
    {
        $this->firestore = $firestore;
    }

    public function index()
    {
        $courses = $this->firestore->database()->collection('suggest_courses')
                ->documents();

        $profs = [];
        $rows = $this->firestore->database()->collection('user_details')
                ->where('role', '=', 'student')
            ->documents();

        foreach($rows as $row) {
            if($row->exists()) {
                $profs[$row->id()] = $row->data()['first_name'].' '.($row->data()['middle_name'] ? ' '.$row->data()['middle_name'].' ' : '').$row->data()['last_name'];
            }
        }

        return view('admin.suggest_course.index', compact('courses', 'profs'));
    }

    public function reject($id)
    {
        $this->firestore->database()->collection('suggest_courses')->document($id)->delete();

        return redirect()->route('admin.course.suggession.index')->with('success', 'Course suggestions rejected successfully.');
    }

}
