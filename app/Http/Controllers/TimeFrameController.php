<?php

namespace App\Http\Controllers;

use App\Models\TimeFrame;
use App\Http\Requests\StoreTimeFrameRequest;
use App\Http\Requests\UpdateTimeFrameRequest;

class TimeFrameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTimeFrameRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTimeFrameRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TimeFrame  $timeFrame
     * @return \Illuminate\Http\Response
     */
    public function show(TimeFrame $timeFrame)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TimeFrame  $timeFrame
     * @return \Illuminate\Http\Response
     */
    public function edit(TimeFrame $timeFrame)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTimeFrameRequest  $request
     * @param  \App\Models\TimeFrame  $timeFrame
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTimeFrameRequest $request, TimeFrame $timeFrame)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TimeFrame  $timeFrame
     * @return \Illuminate\Http\Response
     */
    public function destroy(TimeFrame $timeFrame)
    {
        //
    }
}
