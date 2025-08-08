<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AiTest;
use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
    // ترجع كل المناطق
  
public function index()
{
    $areas = Area::all()->map(function ($area) {
        // عدد المرضى (role = user) في كل منطقة
        $totalUsers = DB::table('users')
            ->where('area_id', $area->id)
            ->where('role', 'user') // فلترة على المرضى فقط
            ->count();

        // عدد الحالات الحمراء (ai_result = Red)
        $redCount = DB::table('ai_tests')
            ->join('users', 'ai_tests.user_id', '=', 'users.id')
            ->where('users.area_id', $area->id)
            ->where('users.role', 'user') // فلترة المرضى
            ->where('ai_tests.ai_result', 'positive')
            ->count();

        // عدد الحالات الخضراء (ai_result = Green)
        $greenCount = DB::table('ai_tests')
            ->join('users', 'ai_tests.user_id', '=', 'users.id')
            ->where('users.area_id', $area->id)
            ->where('users.role', 'user')
            ->where('ai_tests.ai_result', 'negative')
            ->count();

        // عدد الحالات الصفراء (ai_result = Yellow)
        $yellowCount = DB::table('ai_tests')
            ->join('users', 'ai_tests.user_id', '=', 'users.id')
            ->where('users.area_id', $area->id)
            ->where('users.role', 'user')
            ->where('ai_tests.ai_result', 'needs_review')
            ->count();

        return [
            'id' => $area->id,
            'name' => $area->name,
            'totalUsers' => $totalUsers,
            'redCount' => $redCount,
            'greenCount' => $greenCount,
            'yellowCount' => $yellowCount,
            'flag_image' => $area->flag_image 
        ? asset('storage/' . $area->flag_image) 
        : asset('storage/uploads/flags/default-flag.png'),
    'map_image' => $area->map_image 
        ? asset('storage/' . $area->map_image) 
        : asset('storage/uploads/maps/default-map.png'),
        ];
    });

    return response()->json($areas);
}


public function monthlyUsersByArea(Request $request)
{
    $areaId = $request->query('area_id');
    $period = $request->query('period', 'last6'); // last6 أو last12
    $monthsCount = $period === 'last12' ? 12 : 6;

    $currentDate = now();
    $startDate = $currentDate->copy()->subMonths($monthsCount - 1)->startOfMonth();

    $query = DB::table('users')
        ->select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as users')
        )
        ->where('area_id', $areaId)
        ->where('created_at', '>=', $startDate)
        ->groupBy('year', 'month')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();

    // نحتاج نضمن الأشهر حتى لو ما فيها بيانات (يعني نملأ الأشهر الفارغة)
    $result = [];
    for ($i = 0; $i < $monthsCount; $i++) {
        $date = $startDate->copy()->addMonths($i);
        $year = $date->year;
        $month = $date->month;
        $item = $query->firstWhere(function ($q) use ($year, $month) {
            return $q->year == $year && $q->month == $month;
        });

        $result[] = [
            'month' => $date->format('M'),
            'users' => $item ? $item->users : 0,
            'isCurrentMonth' => ($year == $currentDate->year && $month == $currentDate->month),
        ];
    }

    return response()->json($result);
}


public function siteSummary()
{
    // اجلب البيانات من قاعدة البيانات أو حسابها
    $totalPatients = User::where('role', 'user')->count();
    $completedTests = AiTest::count();

    $maleCount = User::where('role', 'user')->where('gender', 'male')->count();
    $femaleCount = User::where('role', 'user')->where('gender', 'female')->count();

    $malePercent = $totalPatients > 0 ? round(($maleCount / $totalPatients) * 100) : 0;
    $femalePercent = $totalPatients > 0 ? round(($femaleCount / $totalPatients) * 100) : 0;

    return response()->json([
        'totalPatients' => $totalPatients,
        'completedTests' => $completedTests,
        'maleCount' => $maleCount,
        'femaleCount' => $femaleCount,
        'malePercent' => $malePercent,
        'femalePercent' => $femalePercent,
    ]);
}


    // ترجع منطقة معينة بالتفصيل (اختياري)
public function show($id)
{
    // جلب المنطقة مع المستخدمين اللي رولهم user فقط مع aiTests
    $area = Area::with(['users' => function($query) {
        $query->where('role', 'user')->with('aiTests');
    }])->find($id);

    if (!$area) {
        return response()->json(['message' => 'Area not found'], 404);
    }

    // إحصائيات بناءً على نتائج AI Tests
    $positive = 0;
    $negative = 0;
    $needsReview = 0;

    foreach ($area->users as $user) {
        foreach ($user->aiTests as $test) {
            switch (strtolower($test->ai_result)) {
                case 'positive':
                    $positive++;
                    break;
                case 'negative':
                    $negative++;
                    break;
                case 'needs_review':
                    $needsReview++;
                    break;
            }
        }
    }

    $stats = [
        ['title' => 'Positive Cases', 'value' => $positive],
        ['title' => 'Negative Cases', 'value' => $negative],
        ['title' => 'Needs Review Cases', 'value' => $needsReview],
    ];

    return response()->json([
        'id' => $area->id,
        'name' => $area->name,
        'description' => $area->description,
        'map_image' => $area->map_image,
        'flag_image' => $area->flag_image,
        'totalUsers' => $area->users->count(),
        'users' => $area->users,
        'stats' => $stats,
    ]);
}





    // إنشاء منطقة جديدة
   public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'map_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'flag_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $mapImagePath = null;
    $flagImagePath = null;

    // احفظ الصور باسم الدولة (lowercase بدون مسافات)
    $baseName = str_replace(' ', '_', strtolower($validated['name']));

    if ($request->hasFile('map_image')) {
        $mapImagePath = $request->file('map_image')
            ->storeAs('uploads/maps', $baseName . '_map.' . $request->file('map_image')->extension(), 'public');
    }

    if ($request->hasFile('flag_image')) {
        $flagImagePath = $request->file('flag_image')
            ->storeAs('uploads/flags', $baseName . '_flag.' . $request->file('flag_image')->extension(), 'public');
    }

    $area = Area::create([
        'name' => $validated['name'],
        'description' => $validated['description'] ?? null,
        'map_image' => $mapImagePath,
        'flag_image' => $flagImagePath,
    ]);

    return response()->json([
        'id' => $area->id,
        'name' => $area->name,
        'map_image' => $area->map_image ? asset('storage/' . $area->map_image) : null,
        'flag_image' => $area->flag_image ? asset('storage/' . $area->flag_image) : null,
    
    ], 201);
}

    // تحديث منطقة
    public function update(Request $request, $id)
    {
        $area = Area::find($id);

        if (!$area) {
            return response()->json(['message' => 'Area not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $area->update($request->only(['name', 'description']));

        return response()->json($area);
    }

    // حذف منطقة
    public function destroy($id)
    {
        $area = Area::find($id);

        if (!$area) {
            return response()->json(['message' => 'Area not found'], 404);
        }

        $area->delete();

        return response()->json(['message' => 'Area deleted successfully']);
    }
}
