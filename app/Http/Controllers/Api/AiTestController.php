<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\TestNotificationMail;
use App\Models\AiTest;
use Illuminate\Http\Request;
use App\Notifications\TestResultNotification;
use Illuminate\Support\Facades\Mail;

class AiTestController extends Controller
{

public function sendNotification(Request  $request){
     $request->validate([
            'test_id' => 'required|integer|exists:ai_tests,id',
            'user_email' => 'required|email',
            'user_name' => 'required|string',
            'message' => 'required|string',
        ]);

        // Send Email
        Mail::raw($request->message, function ($mail) use ($request) {
            $mail->to($request->user_email)
                 ->subject("Health Notification for {$request->user_name}");
        });

        // Update DB
        $test = AiTest::find($request->test_id);
        $test->notification = 1;
        $test->notification_message = $request->message; // تخزين نص الرسالة

        $test->save();

        return response()->json(['success' => true]);
    }



 public function getByUserId($userId){

        if (!$userId) {
            return response()->json([
                'error' => 'user_id query parameter is required'
            ], 400);
        }

        $tests = AiTest::with('aiTestResults' , 'user')->where('user_id', $userId)->get();

        return response()->json($tests );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
