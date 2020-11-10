<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use Kreait\Firebase\Firestore;

class CourseController extends Controller
{

    public function __construct(Firestore $firestore)
    {
        $this->firestore = $firestore;
    }

    public function index()
    {
        $courses = $this->firestore->database()->collection('courses')
                    ->where('professor_id', '=', session('auth')['id'])
                    ->where('is_deleted', '=', false)
                ->documents();
        
        return view('professor.course.index', compact('courses'));
    }

    public function create()
    {
        return view('professor.course.create');
    }

    public function store(CourseRequest $request)
    {
        $data = $request->only(['crn', 'subject', 'name']);
        $data['professor_id'] = session('auth')['id'];
        $data['is_approved'] = 0;
        $data['is_deleted'] = false;
        $data['created_at'] = time();
 
        $this->firestore->database()->collection('courses')->add($data);

        return redirect()->route('professor.course.index')->with('success', 'New course added successfully.');
    }

    public function view($id)
    {
        $course = $this->firestore->database()->collection('courses')->document($id)->snapshot();

        if($course->exists()) {
            $joining_requests = $course->data()['join_requests'] ?? [];
            $students = [];
            
            foreach($joining_requests as $index => $joining_request) {
                $document = $this->firestore->database()->collection('user_details')->document($joining_request['student_id'])->snapshot();
                
                if($document->exists()) {
                    $students[$index] = $document->data();
                    $students[$index]['jr'] = $joining_request;
                }
            }
            return view('professor.course.view', compact('students', 'course'));
        }

        return redirect()->back();
    }

    public function approve($course_id, $id)
    {
        $document = $this->firestore->database()->collection('courses')->document($course_id);

        if($document->snapshot()->exists()) {
            $join_requests = $document->snapshot()->data()['join_requests'] ?? [];
            
            $new_join_requests = array_map(function($request) use ($id) {
                if($request['student_id'] == $id) {
                    $request['status'] = 'Approved';
                }
                
                return $request;
            }, $join_requests);

            $document->update([
                ['path' => 'join_requests', 'value' => $new_join_requests]
            ]);

            return redirect()->route('professor.course.index')->with('success', 'Course approved successfully.');
        }   
    }

    public function reject($course_id, $id)
    {
        $document = $this->firestore->database()->collection('courses')->document($course_id);

        if($document->snapshot()->exists()) {
            $join_requests = $document->snapshot()->data()['join_requests'] ?? [];
            
            $new_join_requests = array_map(function($request) use ($id) {
                if($request['student_id'] == $id) {
                    $request['status'] = 'Rejected';
                }
                
                return $request;
            }, $join_requests);

            $document->update([
                ['path' => 'join_requests', 'value' => $new_join_requests]
            ]);

            return redirect()->route('professor.course.index')->with('danger', 'Course rejected successfully.');
        }   
    }

}
