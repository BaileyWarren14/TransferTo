@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #121212;
        color: #e0e0e0;
        font-family: Arial, sans-serif;
    }

    .chat-container {
        max-width: 600px;
        margin: 20px auto;
        background-color: #1e1e1e;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.5);
    }

    .chat-box {
        height: 400px;
        overflow-y: scroll;
        border: 1px solid #333;
        padding: 10px;
        border-radius: 10px;
        background-color: #2a2a2a;
    }

    .message {
        padding: 8px 12px;
        border-radius: 15px;
        margin: 5px 0;
        max-width: 70%;
        word-wrap: break-word;
    }

    .sent {
        background-color: #4caf50;
        color: #fff;
        margin-left: auto;
        text-align: right;
    }

    .received {
        background-color: #3a3a3a;
        color: #fff;
        margin-right: auto;
        text-align: left;
    }

    .chat-input {
        margin-top: 10px;
        display: flex;
    }

    .chat-input input {
        flex: 1;
        padding: 10px;
        border-radius: 20px;
        border: none;
        margin-right: 10px;
        background-color: #333;
        color: #fff;
    }

    .chat-input button {
        padding: 10px 20px;
        border-radius: 20px;
        border: none;
        background-color: #4caf50;
        color: #fff;
        cursor: pointer;
    }

</style>
<div class="text-center mb-3">
        <a href="{{ route('messages.index') }}" class="btn btn-info px-4 py-2 rounded-pill">
            Return
        </a>
    </div>
<div class="chat-container">
    <h3>Chat con {{ $user->name }} {{ $user->lastname }}</h3>

    <div id="chatBox" class="chat-box"></div>

    <form id="chatForm" class="chat-input">
        @csrf
        <input type="text" id="messageInput" name="message" placeholder="Escribe tu mensaje..." required>
        <input type="hidden" name="client_time" id="client_time">
        <button type="submit">Enviar</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const type = '{{ $type }}';
    const id = '{{ $user->id }}';

    // Función para refrescar mensajes
    function refreshMessages() {
        $.get(`/messages/${type}/${id}/json`, function(data){
            const chatBox = $('#chatBox');
            chatBox.empty();
            data.forEach(msg => {
                const cls = msg.sender_id == {{ Auth::id() }} ? 'sent' : 'received';
                const name = msg.sender_id == {{ Auth::id() }} ? 'Yo' : '{{ $user->name }}';
                chatBox.append(
                    `<div class="message ${cls}">
                        <strong>${name}:</strong> ${msg.message} <br>
                        <small>${msg.created_at}</small>
                    </div>`
                );
            });
            chatBox.scrollTop(chatBox[0].scrollHeight);
        });
    }

    // Llamar al refresco cada 3 segundos
    setInterval(refreshMessages, 3000);
    refreshMessages(); // cargar al inicio

    // Enviar mensaje vía AJAX
    $('#chatForm').submit(function(e){
        e.preventDefault();

        const message = $('#messageInput').val();
        if(message.trim() === '') return;

        // Hora del cliente en formato MySQL
        const now = new Date();
        const client_time = now.getFullYear() + '-' +
                            String(now.getMonth()+1).padStart(2,'0') + '-' +
                            String(now.getDate()).padStart(2,'0') + ' ' +
                            String(now.getHours()).padStart(2,'0') + ':' +
                            String(now.getMinutes()).padStart(2,'0') + ':' +
                            String(now.getSeconds()).padStart(2,'0');

        $('#client_time').val(client_time);

        $.ajax({
            url: `/messages/${type}/${id}`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                message: message,
                client_time: client_time
            },
            success: function(){
                $('#messageInput').val('');
                refreshMessages();
            },
            error: function(xhr){
                alert('Error al enviar mensaje');
            }
        });
    });
</script>

@endsection
