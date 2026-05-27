<?php

namespace App\Http\Controllers;

use App\Models\cr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PythonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('test-python');
    }

    public function process(Request $request) {
        $request->validate([
            'text' => 'required|string'
        ]);

        $response = Http::post('http://localhost:5000/process', [
            'text' => $request->input('text')
        ]);

        $result = $response->json()['result'];
        return back()->with('result', $result);
    }

    public function reverse(Request $request) {
        $request->validate([
            'text' => 'required|string'
        ]);

        $response = Http::post('http://localhost:5000/reverse', [
            'text' => $request->input('text')
        ]);

        $result = $response -> json()['result'];
        return back()->with('result', $result);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(cr $cr)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(cr $cr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, cr $cr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cr $cr)
    {
        //
    }
}
