<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Students;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        return 'Hello from UserController';
    }

    public function login()
    {
        if(View::exists('user.login')){
            return view('user.login')->with('title', 'Sign In');
        }else{
            return abort(404);
        }
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            "email" => ['required', 'email'],
            "password" => 'required'
        ]);

        if(auth()->attempt($validated))
        {
            $request->session()->regenerate();

            return redirect('/')->with('message', 'Welcome to Student System!');
        }

            return back()->withErrors(['email' => 'Incorrect information!'])->onlyInput('email');
    }

    public function register()
    {
        return view('user.register')->with('title', 'Sign Up');
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('message', 'Logout Successfully!');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => ['required', 'min:4'],
            "email" => ['required', 'email', Rule::unique('users', 'email')],
            "password" => 'required|confirmed|min:6'
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $user = User::create($validated);

        return redirect('/register')->with('message', 'Registered Successfully!');
    }

    public function show($id)
    {
        $data = ["data" => "data from database"];
        return view('students.user', $data)
        ->with('data', $data)
        ->with('name', 'PinoyFreeCoder')
        ->with('age', 22)
        ->with('email', 'zhaynekhim@gmail.com')
        ->with('id', $id); 
    }

    public function delete(Request $request) {
        $id = $request->id_to_delete;
        Students::find($id)->delete();

        return redirect('/')->with('message', 'Student Deleted Successfully!');
    }
}
