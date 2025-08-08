<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DistributorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
 public function index()
{
    $distributors = Distributor::with('user')->get();
    return response()->json($distributors);
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
        'name' => 'required|string',
        'email' => 'required|email',
        'phone' => 'required|string|max:20',
        'location' => 'required|string|max:255',
        'area_id' => 'required|exists:areas,id',
        'gender' => 'required|in:male,female',
        'age' => 'required|integer|min:1|max:120',
        'license' => 'nullable|file|mimes:pdf,jpeg,png|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // إنشاء المستخدم أولاً
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt('password'), // أو أرسل كلمة سر مناسبة
        'gender' => $request->gender ,
        'age' => $request->age ,
        'area_id'=>$request->area_id ,
        'phone' => $request->phone ,
        'role' => 'distributor', // مثلا حدد الدور هنا
    ]);

    $distributerData = [
        'user_id' => $user->id,
        'tests' => 0 ,
        'location' => $request->location,
    ];

    if ($request->hasFile('license')) {
        $path = $request->file('license')->store('licenses', 'public');
        $distributerData['license'] = $path;
    }

    $distributer = Distributor::create($distributerData);

    return response()->json([
        'user' => $user,
        'distributer' => $distributer,
    ], 201);
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    $distributor = Distributor::findOrFail($id);
    $user = $distributor->user;

    $validator = Validator::make($request->all(), [
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
        'phone'    => 'required|string|max:20',
        'location' => 'required|string|max:255',
        'gender'   => 'required|in:male,female',
        'age'      => 'required|integer|min:0',
        'area_id'  => 'required|exists:areas,id',
        'license'  => 'nullable|file|mimes:pdf,jpeg,png|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // تحديث بيانات المستخدم
    $user->update([
        'name'  => $request->name,
        'email' => $request->email,
        'gender'   => $request->gender,
        'age'      => $request->age,
        'area_id'  => $request->area_id,
                'phone'    => $request->phone,


    ]);

    // تحديث بيانات الموزّع
    $distributor->update([
        'location' => $request->location,
    ]);

    // تحديث الرخصة إذا تم رفعها من جديد
    if ($request->hasFile('license')) {
        $file = $request->file('license');
        $path = $file->store('licenses', 'public');
        $distributor->license = $path;
        $distributor->save();
    }

    return response()->json(['message' => 'تم التعديل بنجاح', 'distributer' => $distributor->load('user')]);
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  public function destroy($id)
{
    $distributor = Distributor::findOrFail($id);
    $user = $distributor->user;

    // حذف الموزّع والمستخدم المرتبط فيه
    $distributor->delete();
    $user->delete();

    return response()->json(['message' => 'تم الحذف بنجاح']);
}

}
