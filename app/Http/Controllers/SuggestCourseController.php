<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Firestore;
use App\Http\Requests\CourseRequest;

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

        return view('suggest_course.index', compact('courses', 'profs'));
    }
    
    public function create()
    {
        return view('suggest_course.create');
    }

    public function store(CourseRequest $request)
    {
        $data = $request->only(['crn', 'subject', 'name']);
        $data['student_id'] = session('auth')['id'];
        $data['is_approved'] = 0;
        $data['is_deleted'] = false;
        $data['created_at'] = time();
        $data['likes'] = [1,2];
 

        $this->firestore->database()->collection('suggest_courses')->add($data);

        return redirect()->route('course.suggession.index')->with('success', 'New course suggestion added successfully.');
    }

    public function like($id)
    {
        $document = $this->firestore->database()->collection('suggest_courses')->document($id);

        $likes = $document->snapshot()->data()['likes'] ?? [];
        if($document->snapshot()->exists() && !in_array($id, $likes)) {
            $likes[] = session('auth')['id'];
            $document->update([
                ['path' => 'likes', 'value' => $likes]
            ]);

            return redirect()->route('course.suggession.index')->with('success', 'Course liked successfully.');
        }
        return redirect()->route('course.suggession.index')->with('danger', 'Course suggestions not exist or already liked.');
    }

}
