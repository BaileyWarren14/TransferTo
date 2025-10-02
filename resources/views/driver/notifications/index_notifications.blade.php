@extends('layouts.app')

@section('content')
<div class="p-4 bg-gray-900 text-gray-100 min-h-screen">
    <h2 class="text-2xl font-bold mb-4">Notifications</h2>

    <div id="notifications-container">
        @php
            $categories = [
                'inspection' => 'inspection',
                'log' => 'Logs',
                'message' => 'message',
                'time' => 'time',
                'work_order' => 'Work Orders'
            ];
            $colors = [
                'inspection' => 'bg-yellow-500',
                'log' => 'bg-orange-500',
                'message' => 'bg-blue-500',
                'time' => 'bg-purple-500',
                'work_order' => 'bg-green-500'
            ];
        @endphp

        @foreach($categories as $type => $label)
            <div class="mb-6">
                <h3 class="text-xl font-semibold mb-2">{{ $label }}</h3>
                <div class="space-y-2" id="category-{{ $type }}">
                    @forelse($notifications->where('type', $type) as $note)
                        <div class="flex justify-between items-start p-3 rounded-lg {{ $colors[$type] }} text-gray-900" data-id="{{ $note->id }}">
                            <div>
                                <strong>{{ $note->title }}</strong>
                                <p>{{ $note->message }}</p>
                                <small class="text-gray-700">{{ \Carbon\Carbon::parse($note->created_at)->format('d/m/Y H:i') }}</small>
                            </div>
                            @if(!$note->read_at)
                                <form class="mark-read-form" data-id="{{ $note->id }}">
                                    @csrf
                                    <button class="ml-2 bg-gray-800 text-gray-100 px-2 py-1 rounded hover:bg-gray-700" type="submit">Marcar leído</button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-400 italic">No notifications in this category.</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    // Función para actualizar notificaciones vía AJAX
    function fetchNotifications() {
        fetch("{{ route('notifications.json') }}")
            .then(response => response.json())
            .then(data => {
                const categories = ['inspection','log','message','time','work_order'];
                categories.forEach(type => {
                    const container = document.getElementById('category-' + type);
                    container.innerHTML = '';

                    const filtered = data.filter(n => n.type === type);
                    if(filtered.length === 0){
                        container.innerHTML = '<p class="text-gray-400 italic">No notifications in this category.</p>';
                        return;
                    }

                    filtered.forEach(note => {
                        const div = document.createElement('div');
                        div.className = `flex justify-between items-start p-3 rounded-lg bg-gray-700 text-gray-100`;
                        div.dataset.id = note.id;

                        div.innerHTML = `
                            <div>
                                <strong>${note.title}</strong>
                                <p>${note.message}</p>
                                <small class="text-gray-400">${new Date(note.created_at).toLocaleString()}</small>
                            </div>
                            ${!note.read_at ? `
                                <form class="mark-read-form" data-id="${note.id}">
                                    @csrf
                                    <button class="ml-2 bg-gray-800 text-gray-100 px-2 py-1 rounded hover:bg-gray-700" type="submit">Marcar leído</button>
                                </form>
                            ` : ''}
                        `;
                        container.appendChild(div);
                    });
                });

                attachMarkReadEvents();
            });
    }

    // Función para manejar marcar como leído
    function attachMarkReadEvents(){
        document.querySelectorAll('.mark-read-form').forEach(form => {
            form.onsubmit = function(e){
                e.preventDefault();
                const id = this.dataset.id;
                fetch(`/driver/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                }).then(res => {
                    if(res.ok) fetchNotifications();
                });
            }
        });
    }

    // Inicializamos
    fetchNotifications();

    // Actualizar cada 10 segundos
    setInterval(fetchNotifications, 10000);
</script>
@endsection
