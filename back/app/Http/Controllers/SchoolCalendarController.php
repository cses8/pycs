<?php

namespace App\Http\Controllers;

use App\Models\SchoolCalendar;
use App\Http\Requests\StoreSchoolCalendarRequest;
use App\Http\Requests\UpdateSchoolCalendarRequest;
use Log;
use Illuminate\Http\Request;

class SchoolCalendarController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		return SchoolCalendar::where(function ($query) use ($request) {
			if ($request->input('schoolYearId')) {
				$query->where("school_year_id", $request->schoolYearId);
			}

			if ($request->input(key: 'start') && $request->input('end')) {
				$query->where(function ($query2) use ($request) {
					$query2->whereBetween('end', [$request->start, $request->end]);
					$query2->orWhereBetween('start', [$request->start, $request->end]);
				});
			}

			if ($request->input(key: 'upcoming') && $request->upcoming) {
				$currentDate = date('Y-m-d');
				$majorEvent = 42; // 42 days
				$dateUpcoming = date('Y-m-d', strtotime($currentDate . ' + ' . $majorEvent . ' days'));
				$query->whereBetween('start', [$currentDate, $dateUpcoming]);
			}
		})
			->orderBy('start', 'desc')
			->get();
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
	public function store(StoreSchoolCalendarRequest $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 */
	public function show(SchoolCalendar $schoolCalendar)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(SchoolCalendar $schoolCalendar)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateSchoolCalendarRequest $request, SchoolCalendar $schoolCalendar)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(SchoolCalendar $schoolCalendar)
	{
		//
	}
}
