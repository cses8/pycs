<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use App\Http\Requests\StoreSchoolYearRequest;
use App\Http\Requests\UpdateSchoolYearRequest;

class SchoolYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return SchoolYear::all();
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
    public function store(StoreSchoolYearRequest $request)
    {
        $schoolYear = SchoolYear::create($request->validated());

        return response()->json([
            'message' => 'School year created successfully.',
            'schoolYear' => $schoolYear,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolYear $schoolYear)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolYear $schoolYear)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSchoolYearRequest $request, SchoolYear $schoolYear)
    {
        $schoolYear->update($request->validated());

        return response()->json([
            'message' => 'School year updated successfully.',
            'schoolYear' => $schoolYear,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolYear $schoolYear)
    {
        if ($schoolYear->schoolCalendars()->exists()) {
            return response()->json([
                'message' => 'This school year is already used by school calendar records and cannot be deleted.',
            ], 409);
        }

        $schoolYear->delete();

        return response()->json([
            'message' => 'School year deleted successfully.',
        ], 200);
    }
}
