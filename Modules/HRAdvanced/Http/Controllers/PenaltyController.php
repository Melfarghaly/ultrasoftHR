<?php


namespace Modules\HRAdvanced\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRAdvanced\Entities\Penalty;
use Modules\HRAdvanced\Entities\Employee;
use Illuminate\Support\Facades\Storage;

class PenaltyController extends Controller
{
    // Display a list of penalties
    public function index()
    {
        $penalties = Penalty::with('employee')->where('business_id', session('business.id'))->get();
        $employees = Employee::where('business_id', session('business.id'))->get();
        foreach ($penalties as $penalty) {
            if ($penalty->document) {

                $penalty->document_url = Storage::disk('public')->url($penalty->document);
            } else {
                $penalty->document_url = null;
            }
        }
        return view('hradvanced::penalties.index', compact('penalties', 'employees'));

        //return response()->json($penalties);
    }

    // Store a new penalty
    public function store(Request $request)
    {

        $validatedData = $request->validate([

            'employee_id' => 'required',
            'type' => 'required|string',
            'hours' => 'required|numeric',
            'penalty_date' => 'required|date',
            'description' => 'nullable|string',
            'document' => 'nullable|mimes:jpg,png,pdf,txt,xlsx|max:2048',
        ]);
        $validatedData['business_id'] = session('business.id');

        $validatedData['document'] =  $request->file('document') ? $request->file('document')->store('documents/penalties', 'public') : null;


        $validatedData['created_by'] = auth()->user()->id;

        $penalty = Penalty::create($validatedData);
        return redirect()->route('penalties.index')->with('success', 'Penalty created successfully.');

        // return response()->json(['message' => 'Penalty created successfully', 'data' => $penalty]);
    }

    // Show details of a single penalty
    public function show($id)
    {
        $penalty = Penalty::find($id);

        if (!$penalty) {
            return response()->json(['message' => 'Penalty not found'], 404);
        }

        return response()->json($penalty);
    }
    //edit form 
    public function edit($id)
    {
        $penalty = Penalty::with('employee')->find($id);

        if (!$penalty) {
            return response()->json(['message' => 'Penalty not found'], 404);
        }

        return view('hradvanced::penalties.edit', compact('penalty'));
    }
    // Update an existing penalty
    public function update(Request $request, $id)
    {
        $penalty = Penalty::find($id);

        if (!$penalty) {
            return response()->json(['message' => 'Penalty not found'], 404);
        }

        $validatedData = $request->validate([

            'employee_id' => 'required',
            'type' => 'required|string',
            'hours' => 'required|numeric',
            'penalty_date' => 'required|date',
            'description' => 'nullable|string',
            'document' => 'nullable|mimes:jpg,png,pdf,xlsx|max:2048',
        ]);

        if ($request->hasFile('document')) {
            // Delete the old file if a new one is uploaded
            if ($penalty->document) {
                Storage::delete($penalty->document);
            }
            $validatedData['business_id'] = session('business.id');
            $validatedData['document'] = $request->file('document')->store('documents');
        }

        $penalty->update($validatedData);
        return redirect()->route('penalties.index')->with('success', 'Penalty updated successfully.');

        // return response()->json(['message' => 'Penalty updated successfully', 'data' => $penalty]);
    }

    // Delete a penalty
    public function destroy($id)
    {
        $penalty = Penalty::find($id);

        if (!$penalty) {
            return response()->json(['message' => 'Penalty not found'], 404);
        }

        // Delete the associated document if it exists
        if ($penalty->document) {
            Storage::delete($penalty->document);
        }

        $penalty->delete();

        return response()->json(['success' => 'Penalty deleted successfully']);
    }
}
