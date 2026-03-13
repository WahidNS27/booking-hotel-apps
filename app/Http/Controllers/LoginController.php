<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('index');
    }

    public function store(Request $request)
    {
        $user = Login::where('email', $request->email)->first();

        if($user && Hash::check($request->password, $user->password)){
            
            Session::put('user', $user->name);

            return redirect('/dashboard');
        }

        return back()->with('error','Email atau Password salah');
    }

    public function dashboard()
    {
        if(!Session::has('user')){
            return redirect('/');
        }

        return view('dashboard');
    }

    public function logout()
    {
        Session::forget('user');
        return redirect('/');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     */
    public function show(Login $login)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Login $login)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Login $login)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Login $login)
    {
        //
    }
}
