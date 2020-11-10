<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
                    ->where('is_approved', '=', 1)
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

        return view('course.index', compact('courses', 'profs'));
    }

    public function sendRequest($id)
    {
        $document = $this->firestore->database()->collection('courses')->document($id);

        $join_requests = $document->snapshot()->data()['join_requests'] ?? [];
        if($document->snapshot()->exists() && !in_array($id, array_column($join_requests, 'student_id'))) {
            $join_requests[] = ['student_id' => session('auth')['id'], 'status' => "Pending"];
            $document->update([
                ['path' => 'join_requests', 'value' => $join_requests]
            ]);

            return redirect()->route('course.index')->with('success', 'Course request added successfully.');
        }
        return redirect()->route('course.index')->with('danger', 'Course not exist or already requested.');
    }
}
