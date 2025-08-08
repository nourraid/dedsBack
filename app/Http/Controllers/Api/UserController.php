<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index()
{
        $users = User::with(['area', 'aiTests'])->get()->map(function ($user) {
        $latestTest = $user->aiTests->sortByDesc('created_at')->first();
        

         return [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'id_number' => $user->id_number,
            'dob' => $user->dob,
            'gender' => $user->gender,
            'blood_type' => $user->blood_type,
            'weight' => $user->weight,
            'country' => $user->country,
            'city' => $user->city,
            'street' => $user->street,
            'building_number' => $user->building_number,
            'age' => $user->age,
            'area_id' => $user->area_id,
            'profile_picture' => $user->profile_picture,
            'area' => $user->area?->name,
            'registration_date' => $user->created_at->format('Y-m-d'),
            'ai_result' => optional($latestTest)->ai_result ?? 'no tests',
        ];

    
    });
    


    return response()->json($users);
}


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|string|max:20',
        'role' => 'required|string',
        'id_number' => 'required|string',
        'password' => 'required|string|min:6',
        'gender' => 'nullable|string',
        'dob' => 'nullable|date',
        'blood_type' => 'nullable|string|max:3',
        'country' => 'nullable|string|max:100',
        'city' => 'nullable|string|max:100',
        'street' => 'nullable|string|max:255',
        'building_number' => 'nullable|string|max:50',
        'weight' => 'nullable|numeric',
        'area_id' => 'nullable|exists:areas,id',

        'profile_picture' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $data = $request->all();

    // حساب العمر إذا موجود تاريخ الميلاد
    if (!empty($data['dob'])) {
        $dob = \Carbon\Carbon::parse($data['dob']);
        $data['age'] = $dob->age;  // خاصية age تحسب العمر تلقائياً
    } else {
        $data['age'] = null;
    }

    // معالجة الصورة إذا وُجدت
    if ($request->hasFile('profile_picture')) {
        $file = $request->file('profile_picture');
        $path = $file->store('profile_pictures', 'public');
        $data['profile_picture'] = $path;
    }

    $data['password'] = Hash::make($data['password']);

    $user = User::create($data);

    return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
}



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function test()
{
    return response()->json(['message' => 'API is working']);
}

public function show($userId)
{
    // جلب بيانات المستخدم مع التحاليل والنتائج
    $user = User::with('aiTests.aiTestResults')->find($userId);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // تعاريف مسبقة
    $hasDiabetes = false;
    $kidneyDisease = false;
    $utiInfection = false;

    // قواعد استنتاجية مبسطة (يمكن تعديلها حسب المعايير الدقيقة)
    
foreach ($user->aiTests ?? [] as $test) {
        foreach ($test->results ?? [] as $result) {
            $name = strtolower($result->name);
            $value = strtolower($result->value);

            // مرض السكري
            if (($name == 'glucose' || $name == 'ketone') && (strpos($value, 'positive') !== false || is_numeric($value) && floatval($value) > 0)) {
                $hasDiabetes = true;
            }

            // مشاكل الكلى
            if (($name == 'micro albumin' || $name == 'creatinine') && is_numeric($value) && floatval($value) > 10) {
                $kidneyDisease = true;
            }

            // التهابات المسالك البولية
            if (($name == 'leukocytes' || $name == 'nitrite') && (strpos($value, 'positive') !== false || $value != 'negative')) {
                $utiInfection = true;
            }
        }
    }

    $response = [
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'id_number' => $user->id_number,
            'phone' => $user->phone,
            'dob' => $user->dob,
            'gender' => $user->gender,
            'blood_type' => $user->blood_type,
            'country' => $user->country,
            'city' => $user->city,
            'street' => $user->street,
            'building_number' => $user->building_number,
            'weight' => $user->weight,
            'profile_picture' => $user->profile_picture,
        ],
        'medical_info' => [
            'has_diabetes' => $hasDiabetes,
            'kidney_disease' => $kidneyDisease,
            'uti_infection' => $utiInfection,
            'comments' => $user->medical_comments ?? null,
        ],
    ];

    return response()->json($response);
}



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'name' => 'sometimes|required|string|max:255',
        'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
        'phone' => 'sometimes|required|string|max:20',
        'role' => 'sometimes|required|string',
        'gender' => 'nullable|string',
        'dob' => 'nullable|date',
        'blood_type' => 'nullable|string|max:3',
        'country' => 'nullable|string|max:100',
        'city' => 'nullable|string|max:100',
        'street' => 'nullable|string|max:255',
        'building_number' => 'nullable|string|max:50',
        'weight' => 'nullable|numeric',
        'area_id' => 'nullable|exists:areas,id',
        'profile_picture' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
        'password' => 'nullable|string|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $data = $request->all();

    if ($request->hasFile('profile_picture')) {
        $file = $request->file('profile_picture');
        $path = $file->store('profile_pictures', 'public');
        $data['profile_picture'] = $path;
    }

    if (!empty($data['password'])) {
        $data['password'] = Hash::make($data['password']);
    } else {
        unset($data['password']);
    }

    $user->update($data);

    return response()->json(['message' => 'User updated successfully', 'user' => $user]);
}

    public function getAdmins()
{
    $admins = User::where('role', 'admin')->get();
    return response()->json($admins);
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function destroy($id)
{
    $admin = User::find($id);
    if (!$admin) {
        return response()->json(['message' => 'المشرف غير موجود'], 404);
    }
    $admin->delete();

    return response()->json(['message' => 'تم الحذف بنجاح']);
}

// app/Http/Controllers/UserController.php

public function destroyuser($id)
{
    try {
        $user = User::findOrFail($id);

        // حذف اختبارات الذكاء الاصطناعي المرتبطة
        $user->aiTests()->delete();

        // حذف الموفّر المرتبط إن وجد
        if ($user->provider) {
            $user->provider()->delete();
        }

        // حذف المستخدم نفسه
        $user->delete();

        return response()->json(['message' => 'تم حذف المستخدم بنجاح']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'حدث خطأ أثناء حذف المستخدم.'], 500);
    }
}

}
