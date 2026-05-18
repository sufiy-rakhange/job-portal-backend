<?php

namespace App\Http\Controllers;

use App\Models\MyJob;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = MyJob::query();

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        return $query->paginate(5);
    }


    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10',
            'company' => 'required',
            'location' => 'required',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $validated['user_id'] = $request->user()->id;

        $job = MyJob::create($validated);

        return response()->json($job, 201);
    }

    public function show(int $id)
    {
        return MyJob::findOrFail($id);
    }

    public function update(Request $request, int $id)
    {
        $job = MyJob::findOrFail($id);

        if ($job->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10',
            'company' => 'required',
            'location' => 'required',
            'logo' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $job->update($validated);

        return response()->json($job);
    }

    public function destroy(int $id)
    {
        $job = MyJob::findOrFail($id);
        $job->delete();

        return response()->json(null, 204);
    }
}
