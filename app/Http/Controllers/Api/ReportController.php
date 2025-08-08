<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\AiTest;
use App\Models\AiTestResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

public function allCountries(){
      $countries = DB::table('users')
        ->select('country')
        ->distinct()
        ->whereNotNull('country')
        ->orderBy('country')
        ->pluck('country');

    return response()->json($countries);
}
public function byCountry(Request $request)
{
    $country = $request->query('country'); // قيمة الدولة المرسلة من الرياكت

    $query = DB::table('users')
        ->select('country', DB::raw('COUNT(*) as cases'))
        ->whereNotNull('country');

    if ($country && strtolower($country) !== 'all') {
        $query->where('country', $country);
    }

    $results = $query->groupBy('country')->get();

    return response()->json($results);
}


public function byGender(Request $request)
{
    $gender = $request->query('gender');

    $query = DB::table('users')
        ->select('gender', DB::raw('COUNT(*) as count'))
        ->whereNotNull('gender');

    if ($gender && strtolower($gender) !== 'all') {
        $query->where('gender', $gender);
    }

    $results = $query->groupBy('gender')->get();

    return response()->json($results);
}


public function byDateRange(Request $request)
{
    $from = $request->query('from');
    $to = $request->query('to');
    $granularity = $request->query('granularity', 'monthly');

    if (!$from || !$to) {
        return response()->json(['message' => 'Missing date range'], 400);
    }

    $query = DB::table('ai_tests')->whereBetween('created_at', [$from, $to]);

    if ($granularity === 'daily') {
        $results = $query
            ->selectRaw('DATE(created_at) as period, COUNT(*) as count')
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    } elseif ($granularity === 'monthly') {
        $results = $query
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as period, COUNT(*) as count')
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    } elseif ($granularity === 'yearly') {
        $results = $query
            ->selectRaw('YEAR(created_at) as period, COUNT(*) as count')
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    } else {
        return response()->json(['message' => 'Invalid granularity'], 400);
    }

    return response()->json($results);
}




public function patientReport($patientId)
{
    $patient = User::with('area')->find($patientId);

    if (!$patient) {
        return response()->json(['message' => 'Patient not found'], 404);
    }

    $latestTest = AiTest::with('aiTestResults')
        ->where('user_id', $patientId)
        ->latest('created_at')
        ->first();

    if (!$latestTest) {
        return response()->json(['message' => 'No AI test results found for this patient'], 404);
    }

    $data = [
        'patientId' => $patient->id,
        'name' => $patient->name,
        'age' => $patient->age,
        'gender' => $patient->gender,
        'medicalCenter' => optional($patient->area)->name ?? 'N/A',
        'testDate' => $latestTest->created_at->format('Y-m-d H:i'),
        'tests' => $latestTest->aiTestResults->map(function ($result) {
            return [
                'name' => $result->name,
                'result' => $result->value,
                'standard' => $result->standard,
            ];
        }),
    ];

    return response()->json($data);
}



}

