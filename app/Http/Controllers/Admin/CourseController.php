<?php

namespace App\Http\Controllers\admin;

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
                    ->where('is_deleted', '=', false)
                ->documents();

        $profs = [];
        $rows = $this->firestore->database()->collection('user_details')
                ->where('role', '=', 'professor')
            ->documents();

        foreach($rows as $row) {
            if($row->exists()) {
                $profs[$row->id()] = $row->data()['first_name'].' '.($row->data()['middle_name'] ? ' '.$row->data()['middle_name'].' ' : '').$row->data()['last_name'];
            }
        }
        
        return view('admin.course.index', compact('courses', 'profs'));
    }

    public function create()
    {
        return view('admin.course.create');
    }

    public function store(CourseRequest $request)
    {
        $data = $request->only(['crn', 'subject', 'name']);
        $data['admin_id'] = session('auth')['id'];
        $data['is_approved'] = 1;
        $data['is_deleted'] = false;
        $data['created_at'] = time();
 
        $this->firestore->database()->collection('courses')->add($data);

        if($request->get('sid')) {
            $this->firestore->database()->collection('suggest_courses')->document($request->get('sid'))->delete();
        }

        return redirect()->route('admin.course.index')->with('success', 'New course added successfully.');
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
            return view('admin.course.view', compact('students', 'course'));
        }

        return redirect()->back();
    }

    public function approveCourse($course_id)
    {
        $document = $this->firestore->database()->collection('courses')->document($course_id);

        if($document->snapshot()->exists()) {
            $document->update([
                ['path' => 'is_approved', 'value' => 1]
            ]);

            return redirect()->route('admin.course.index')->with('success', 'Course approved successfully.');
        }
    }
    
    public function rejectCourse($course_id)
    {
        $document = $this->firestore->database()->collection('courses')->document($course_id);

        if($document->snapshot()->exists()) {
            $document->update([
                ['path' => 'is_approved', 'value' => 2]
            ]);

            return redirect()->route('admin.course.index')->with('success', 'Course rejected successfully.');
        }
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

            return redirect()->route('admin.course.view', $course_id)->with('success', 'Course join request approved successfully.');
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

            return redirect()->route('admin.course.view', $course_id)->with('danger', 'Course join request rejected successfully.');
        }   
    }

}
