{{-- resources/views/test/mp-checkout.blade.php --}}
<h1>🧪 SIMULADOR DE MERCADO PAGO (TESTING)</h1>
<p>Esto simula un pago exitoso sin usar Mercado Pago real</p>

<form method="POST" action="{{ route('test.mp.simulate') }}">
    @csrf
    <input type="hidden" name="plan_id" value="{{ $planId }}">
    <button type="submit" class="btn btn-success">
        ✅ Simular Pago Exitoso
    </button>
</form>
