<?php

namespace App\Http\Controllers;

use App\Models\FormControl;
use Illuminate\Http\Request;

class FormControlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function getSelectables($key): \Illuminate\Http\JsonResponse
    {
        $formControl = FormControl::where('key', $key)->firstOrFail();
        $value = json_decode($formControl->value, true); // Convert JSON string to PHP array

        return response()->json($value);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FormControl $formControl)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FormControl $formControl)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FormControl $formControl)
    {
        //
    }
}
