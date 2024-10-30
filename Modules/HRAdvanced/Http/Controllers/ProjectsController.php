<?php

namespace Modules\HRAdvanced\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HRAdvanced\Entities\Project;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $projects = Project::where('business_id', session('business.id'))->get();

        return view('hradvanced::projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('hradvanced::projects.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([

            'name' => 'required',
            'code' => 'required',
            'description' => 'required',
        ]);
        $validatedData['business_id'] = session('business.id');
        Project::create($validatedData);

        return redirect()->back()->with('success', 'Project created successfully.');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);

        return view('hradvanced::projects.show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $project = Project::findOrFail($id);
        return view('hradvanced::projects.edit', get_defined_vars());
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

            'name' => 'required',
            'code' => 'required',
        ]);
        $validatedData['business_id'] = session('business.id');
        Project::whereId($id)->update($validatedData);

        return redirect()->route('hr_projects.index')->with('success', 'project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->back()->with('success', 'Project deleted successfully.');
    }
}
