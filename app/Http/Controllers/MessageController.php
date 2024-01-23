<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Notifications\ThankYouForConnectingNotification;
use Illuminate\Http\Request;
use Notification;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Message::latest()->get();
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
            'sender_email' => 'required|email',
            'message' => 'required|string|max:1024|min:5',
            'sender_name' => 'required'
        ]);

        $message = Message::create($data);

        $first_name = explode(' ', trim($data['sender_name']))[0];

        Notification::route('mail', $data['sender_email'])
            ->notify(new ThankYouForConnectingNotification($first_name));

        return response()->json($message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        return response()->json($message);
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
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
