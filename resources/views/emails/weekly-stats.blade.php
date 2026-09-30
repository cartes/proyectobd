<x-mail::message>
    # ¡Tu resumen semanal de BigDad, {{ $name }}! 📈

    Aquí tienes lo que ha pasado en tu perfil esta última semana:

    | Métrica | Cantidad |
    | :------- | :------- |
    | **Nuevos Likes** | {{ $stats['likes'] }} |
    | **Visitas al Perfil** | {{ $stats['views'] }} |
    | **Nuevos Mensajes** | {{ $stats['messages'] }} |

    @if($stats['likes'] > 0)
        ¡Parece que tienes pretendientes esperando! No les hagas esperar.
    @endif

    <x-mail::button :url="$url">
        Ir a mi Dashboard
    </x-mail::button>

    ¿Sabías que los usuarios **Premium** reciben hasta 5 veces más visitas? ¡Potencia tu perfil hoy mismo!

    Saludos,<br>
    {{ config('app.name') }}
</x-mail::message>