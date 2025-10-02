<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Driver;
use App\Models\Admin;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    
        // Lista de usuarios para iniciar chat
    public function index()
    {
         // Traer todos los drivers y admins MENOS el usuario autenticado
        $drivers = Driver::select('id', 'name', 'lastname', 'email')
            ->where('id', '!=', Auth::id()) // evitar que aparezca él mismo
            ->get();

        $admins = Admin::select('id', 'name', 'lastname', 'email')
            ->where('id', '!=', Auth::id()) // igual acá
            ->get();

        return view('driver.messages.index_messages', compact('drivers', 'admins'));
    }

    public function chat($type, $id)
    {
        // Buscar usuario según el tipo
        $user = $type === 'driver'
            ? Driver::find($id)
            : Admin::find($id);

        if (!$user) {
            abort(404, "Usuario no encontrado");
        }

        $authType = Auth::user() instanceof \App\Models\Driver ? 'driver' : 'admin';

        // Traer mensajes entre el usuario autenticado y el seleccionado
        $messages = Message::where(function ($q) use ($id, $type, $authType) {
                $q->where('sender_id', Auth::id())
                ->where('sender_type', $authType)
                ->where('receiver_id', $id)
                ->where('receiver_type', $type);
            })->orWhere(function ($q) use ($id, $type, $authType) {
                $q->where('sender_id', $id)
                ->where('sender_type', $type)
                ->where('receiver_id', Auth::id())
                ->where('receiver_type', $authType);
            })
            ->orderBy('created_at')
            ->get();

        return view('driver.messages.chat', compact('user', 'messages', 'type'));
    }

   public function send(Request $request, $type, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'client_time' => 'required|date_format:Y-m-d H:i:s',
        ]);

        $senderType = Auth::user() instanceof \App\Models\Driver ? 'driver' : 'admin';

        Message::create([
            'sender_id'     => Auth::id(),
            'sender_type'   => $senderType,
            'receiver_id'   => $id,
            'receiver_type' => $type,
            'message'       => $request->message,
            'created_at'    => $request->client_time,
            'updated_at'    => $request->client_time,
        ]);

        return response()->json(['success' => true]); // importante para AJAX
    }


    public function messagesJson($type, $id)
    {
        $messages = Message::where(function ($q) use ($id) {
                $q->where('sender_id', Auth::id())->where('receiver_id', $id);
            })->orWhere(function ($q) use ($id) {
                $q->where('sender_id', $id)->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at')
            ->get();

        return response()->json($messages);
    }

}
