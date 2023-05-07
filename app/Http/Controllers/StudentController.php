<?php

namespace App\Http\Controllers;

use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index()
    {
        $data = array("students" => DB::table('students')->orderBy('created_at', 'desc')->simplePaginate(10));
        return view('students.index', $data);
    }

    public function show($id)
    {
        $data = Students::findOrFail($id);

        return view('Students.edit', ['student' => $data])->with('title', 'Student Information');
    }

    public function create()
    {
        return view('students.create')->with('title', 'Add New');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "first_name" => ['required', 'min:4'],
            "last_name" => ['required', 'min:4'],
            "gender" => ['required'],
            "age" => ['required'],
            "email" => ['required', 'email', Rule::unique('students', 'email')]
        ]);

        Students::create($validated);

        return redirect('/')->with('message', 'New Student Was Added Successfully!');
    }

    public function update(Request $request, Students $student)
    {
        $validated = $request->validate([
            "first_name" => ['required', 'min:4'],
            "last_name" => ['required', 'min:4'],
            "gender" => ['required'],
            "age" => ['required'],
            "email" => ['required', 'email'],
        ]);

        $student->update($validated);

        return back()->with('message', 'Information Successfully Updated!');
    }

    public function destroy(Students $student)
    {
        $student->delete();

        return redirect('/')->with('message', 'Student Deleted Successfully!');
    }
}
