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
            'upper_text' => 'required|string'
        ]);

        $response = Http::post('http://localhost:5000/process', [
            'upper_text' => $request->input('upper_text')
        ]);

        $result = $response->json()['result_upper'];
        return back()->with('result_upper', $result);
    }

    public function reverse(Request $request) {
        $request->validate([
            'reverse_text' => 'required|string'
        ]);

        $response = Http::post('http://localhost:5000/reverse', [
            'reverse_text' => $request->input('reverse_text')
        ]);

        $result = $response -> json()['result_reverse'];
        return back()->with('result_reverse', $result);
    }

    public function langDetect(Request $request) {
        $request->validate([
            'predetect_text' => 'required|string'
        ]);

        $response = Http::post('http://localhost:5000/lang_detect', [
            'predetect_text' => $request->input('predetect_text')
        ]);

        $result = $response -> json()['result_detection'];
        return back()->with('result_detection', $result);
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
