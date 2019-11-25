<?php

namespace App\Http\Controllers;

use App\Models\Message\Message;
use App\User;
use Illuminate\Http\Request;
use Auth;

class ChatController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the chat screen.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $authUser = Auth::user();
        $userId = $request->route('userId');

        $user = User::where('id', $userId)->where('id', '<>', $authUser->id)->firstOrFail();
        $messages = Message::where(function ($query) use ($authUser, $user) {
            $query->where('from_user_id', $authUser->id)->where('to_user_id', $user->id);
        })
            ->orWhere(function ($query) use ($authUser, $user) {
                $query->where('to_user_id', $authUser->id)->where('from_user_id', $user->id);
            })
            ->latest()
            ->get();

        return view('chat')->with([
            'authUser' => $authUser,
            'user' => $user,
            'messages' => $messages
        ]);
    }

    public function submit(Request $request)
    {
        $authUser = Auth::user();

        $data = $request->validate([
            'to_user_id' => 'required|integer|exists:users,id',
            'message' => 'required|string|max:65000'
        ]);

        if ($data['to_user_id'] == $authUser->id) {
            abort(404);
        }

        $message = new Message();
        $message->from_user_id = Auth::id();
        $message->to_user_id = $data['to_user_id'];
        $message->message = $data['message'];
        $message->save();

        return response()->json([
            'status' => 'OK',
            'message' => $message
        ]);
    }
}
