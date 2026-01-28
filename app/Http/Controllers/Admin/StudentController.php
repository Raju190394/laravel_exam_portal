<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
            'courses' => 'required|array',
        ]);

        Excel::import(new StudentsImport($request->courses), $request->file('file'));

        return back()->with('success', 'Students imported and enrolled successfully.');
    }
    public function index()
    {
        $students = User::where('role', 'student')
            ->with('courses')
            ->latest()
            ->paginate(10);
        return view('pages.admin.students.index', compact('students'));
    }

    public function create()
    {
        $courses = Course::where('is_active', true)->get();
        return view('pages.admin.students.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'courses' => 'required|array',
            'courses.*' => 'exists:courses,id',
        ]);

        $student = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        $student->courses()->sync($request->courses);

        return redirect()->route('admin.students.index')->with('success', 'Student created and enrolled successfully.');
    }

    public function edit(User $student)
    {
        if ($student->role !== 'student') abort(404);
        
        $courses = Course::where('is_active', true)->get();
        $student->load('courses');
        return view('pages.admin.students.edit', compact('student', 'courses'));
    }

    public function update(Request $request, User $student)
    {
        if ($student->role !== 'student') abort(404);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $student->id,
            'password' => 'nullable|string|min:8|confirmed',
            'courses' => 'required|array',
            'courses.*' => 'exists:courses,id',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $student->update($data);
        $student->courses()->sync($request->courses);

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(User $student)
    {
        if ($student->role !== 'student') abort(404);
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Student removed successfully.');
    }
}
