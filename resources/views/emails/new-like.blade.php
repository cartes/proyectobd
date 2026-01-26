<x-mail::message>
    # ¡Hola {{ $recipientName }}!

    Alguien ha mostrado interés en tu perfil de **Big-Dad**... ❤️

    **{{ $likerName }}** te ha dado un like. ¿Quieres ver quién es y si hay match?

    <x-mail::button :url="$url">
        Ver Perfil de {{ $likerName }}
    </x-mail::button>

    Si no quieres recibir estas notificaciones, puedes ajustar tus preferencias en la configuración de tu cuenta.

    Gracias,<br>
    El equipo de {{ config('app.name') }}
</x-mail::message>