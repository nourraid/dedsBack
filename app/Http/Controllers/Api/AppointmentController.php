<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Provider;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Appointment::with('provider')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
public function store(Request $request)
{
    $validated = $request->validate([
        'ai_test_id' => 'required|exists:ai_tests,id',
        'datetime' => 'required|date',
        'note' => 'nullable|string',
        'provider_id' => 'nullable|exists:providers,id',
    ]);

    $appointment = Appointment::create([
    'ai_test_id' => $request->ai_test_id,
    'datetime' => $request->datetime,
    'note' => $request->note,
    'provider_id' => $request->provider_id,
    ]);

$provider = Provider::find($request->provider_id);
if ($provider) {
    $provider->tests = $provider->tests + 1;
    $provider->save();
}

    return response()->json(['message' => 'تم إنشاء الموعد بنجاح', 'appointment' => $appointment]);
}




    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment)
    {
        return $appointment->load('provider');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appointment $appointment)
    {
   $appointment->update($request->all());
        return $appointment;    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
  $appointment->delete();
        return response()->noContent();    }
}
