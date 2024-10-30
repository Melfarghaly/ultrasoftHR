<?php

namespace Modules\HRAdvanced\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HRAdvanced\Entities\Job;
use Illuminate\Contracts\Support\Renderable;
use Modules\HRAdvanced\Entities\Employee;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        $jobs = Job::where('business_id', session('business.id'))->get();;
        return view('hradvanced::jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('hradvanced::jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([

            'job_title' => 'required',
            'job_description' => 'required',
        ]);
        $validatedData['business_id'] = session('business.id');
        Job::create($validatedData);

        return redirect()->back()->with('success', 'Job created successfully.');
    }
    public function jobStore(Request $request)
    {
        $validatedData = $request->validate([

            'job_title' => 'required',
            'job_description' => 'required',
        ]);
        $validatedData['business_id'] = session('business.id');
        $job = Job::create($validatedData);

        return response()->json([
            'success' => 'Job created successfully.',
            'jobId' => $job->id,
            'jobTitle' => $job->job_title,
            'jobDescription' => $job->job_description,

        ]);
    }
    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $job = Job::findOrFail($id);
        return view('hradvanced::jobs.show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $job = Job::findOrFail($id);
        return view('hradvanced::jobs.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([

            'job_title' => 'required',
            'job_description' => 'required',
        ]);
        $validatedData['business_id'] = session('business.id');
        Job::whereId($id)->update($validatedData);

        return redirect()->route('hr_jobs.index')->with('success', 'Job updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        // if(Employee::where('job_id',$id)->count()){
        //     return redirect()->route('hr_jobs.index')->with('error', 'You cannot delete this job');
        // }
        $job = Job::findOrFail($id);
        $job->delete();

        return redirect()->back()->with('success', 'Job deleted successfully.');
    }
}
