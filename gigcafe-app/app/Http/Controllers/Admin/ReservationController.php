<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TableStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationStoreRequest;
use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::all();
        return view('admin.reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tables = Table::where('status', TableStatus::Available)->get();
        return view('admin.reservations.create', compact('tables'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReservationStoreRequest $request)
    {
        try {
            $table = Table::findOrFail($request->table_id);
    
            if ($request->guest_number > $table->guest_number) {
                return back()->with('warning', 'Please choose the table based on the number of guests.');
            }
    
            $request_date = Carbon::parse($request->res_date);
    
            foreach ($table->reservations as $res) {
                $reservationDate = Carbon::parse($res->res_date);
    
                if ($reservationDate->isSameDay($request_date)) {
                    return back()->with('warning', 'This table is reserved for the selected date.');
                }
            }
    
            Reservation::create($request->validated());
    
            return redirect()->route('admin.reservations.index')->with('success', 'Reservation created successfully.');
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Table not found.');
        }
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
    public function edit(Reservation $reservation)
    {
        $tables = Table::where('status', TableStatus::Available)->get();
        return view('admin.reservations.edit', compact('reservation', 'tables'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReservationStoreRequest $request, Reservation $reservation)
    {
        $table = Table::findOrFail($request->table_id);
        if ($request->guest_number > $table->guest_number) {
            return back()->with('warning', 'Please choose the table base on guests.');
        }
        $request_date = Carbon::parse($request->res_date);
    
        foreach ($table->reservations as $res) {
            $reservationDate = Carbon::parse($res->res_date);

            if ($reservationDate->isSameDay($request_date)) {
                return back()->with('warning', 'This table is reserved for this date.');
            }
        }

        $reservation->update($request->validated());
        return to_route('admin.reservations.index')->with('success', 'Reservation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return to_route('admin.reservations.index')->with('warning', 'Reservation deleted successfully.');
    }
}
