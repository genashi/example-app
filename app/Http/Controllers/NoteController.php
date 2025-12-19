<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = auth()->user()->notes()->get();
        $categories = $notes->pluck('category')->filter()->unique()->sort()->values();

        // Group notes by category
        $notesByCategory = $categories->mapWithKeys(function ($category) use ($notes) {
            return [$category => $notes->where('category', $category)->values()];
        });

        return view('notes.index', [
            'categories' => $categories,
            'notesByCategory' => $notesByCategory,
        ]);
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
        $data = $request->validate([
            'title'    => 'nullable|string|max:255',
            'content'  => 'nullable|string',
            'category' => 'required|string|max:255'
        ]);

        // Set default values if empty
        $data['title'] = $data['title'] ?? '';
        $data['content'] = $data['content'] ?? '';

        auth()->user()->notes()->create([
            'title'     => $data['title'],
            'content'   => $data['content'],
            'category'  => $data['category'],
            'is_completed' => false,
        ]);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * Saves title/content (and optional category), and toggles is_completed status if provided.
     */
    public function update(Request $request, Note $note)
    {
        // Authorization
        if (auth()->id() !== $note->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'title'        => 'sometimes|string|max:255',
            'content'      => 'sometimes|nullable|string',
            'category'     => 'sometimes|string|max:255',
            'is_completed' => 'sometimes|boolean',
        ]);

        // Update fields if present in input
        if ($request->filled('title')) {
            $note->title = $request->input('title');
        }
        if ($request->has('content')) {
            $note->content = $request->input('content');
        }
        if ($request->filled('category')) {
            $note->category = $request->input('category');
        }
        if ($request->has('is_completed')) {
            $note->is_completed = $request->boolean('is_completed');
        }

        $note->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        // Manually check permission as authorize() is unavailable
        if (auth()->id() !== $note->user_id) {
            abort(403, 'Unauthorized action.');
        }
        $note->delete();
        return back();
    }
}
