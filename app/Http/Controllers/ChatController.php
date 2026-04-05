<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function landing()
    {
        return view('auth.login');
    }

    public function index()
    {
        return view('chat.index');
    }

    public function enter(Request $request)
    {
        $request->session()->put('username', $request->username);
        return redirect()->route('chat.index');
    }
}
