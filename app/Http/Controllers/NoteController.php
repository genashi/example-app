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
     * Toggles is_completed or edits note details based on provided data.
     */
    public function update(Request $request, Note $note)
    {
        // Manually check permission as the 'authorize' method is missing
        if (auth()->id() !== $note->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'title'        => 'sometimes|string|max:255',
            'content'      => 'sometimes|nullable|string',
            'category'     => 'sometimes|string|max:255',
            'is_completed' => 'sometimes|boolean',
        ]);

        // Only toggle is_completed if field is present
        if ($request->has('is_completed')) {
            $note->is_completed = $request->boolean('is_completed');
        }

        // Update other note details if present
        if (array_key_exists('title', $data)) {
            $note->title = $data['title'];
        }
        if (array_key_exists('content', $data)) {
            $note->content = $data['content'];
        }
        if (array_key_exists('category', $data)) {
            $note->category = $data['category'];
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
