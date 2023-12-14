<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomStoreRequest;
use App\Models\Category;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::all();
        return view('admin.rooms.room', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.rooms.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoomStoreRequest $request)
    {
        $image = $request->file('image')->store('public/rooms');

        $room = Room::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image,
            'guest_number' => $request->guest_number,
            'status' => $request->status,
            'price' => $request->price
        ]);

        if ($request->has('categories')) {
            $room->categories()->attach($request->categories);
        }

        return to_route('admin.rooms.index')->with('success', 'Room created successfully.');
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
    public function edit(Room $room)
    {
        $categories = Category::all();
        return view('admin.rooms.edit', compact('room', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'guest_number' => 'required',
            'status' => 'required',
            'price' => 'required'
        ]);
        $image = $room->image;
        if ($request->hasFile('image')) {
            Storage::delete($room->image);
            $image = $request->file('image')->store('public/rooms');
        }

        $room->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image,
            'guest_number' => $request->guest_number,
            'status' => $request->status,
            'price' => $request->price
        ]);

        if ($request->has('categories')) {
            $room->categories()->sync($request->categories);
        }
        return to_route('admin.rooms.index')->with('success', 'Room updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        Storage::delete($room->image);
        $room->categories()->detach();
        $room->delete();
        return to_route('admin.rooms.index')->with('danger', 'Room deleted successfully.');
    }
}
