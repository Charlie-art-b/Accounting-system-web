@php
    $totalActivos = $p['total_activos'] ?? 0;
    $totalPasivoPatrimonio = $p['total_pasivos_patrimonio'] ?? 0;
    $balanceado = $p['ecuacion_balanceada'] ?? false;
    $diff = $p['diferencia'] ?? 0;

    $rows = $p['detalles'] ?? [];

    // colores
    $colorTotalActivos = $totalActivos < 0 ? '#dc2626' : '#111827';
    $colorTotalPP = $totalPasivoPatrimonio < 0 ? '#dc2626' : '#111827';
    $colorBalanceado = $balanceado ? '#16a34a' : '#dc2626';
@endphp

{{-- RESUMEN --}}
<div style="display:grid; grid-template-columns: 1fr; gap:14px; margin-bottom:18px;">
    <div style="display:grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap:14px;">
        {{-- Total activos --}}
        <div style="border:1px solid #e5e7eb; border-radius:14px; padding:16px 18px; background:white;">
            <div style="font-size:12px; color:#6b7280; margin-bottom:6px;">Total Activos</div>
            <div style="font-size:24px; font-weight:800; color: {{ $colorTotalActivos }}; font-variant-numeric: tabular-nums;">
                {{ number_format($totalActivos, 2) }}
            </div>
        </div>

        {{-- Total pasivo + patrimonio --}}
        <div style="border:1px solid #e5e7eb; border-radius:14px; padding:16px 18px; background:white;">
            <div style="font-size:12px; color:#6b7280; margin-bottom:6px;">Total Pasivo + Patrimonio</div>
            <div style="font-size:24px; font-weight:800; color: {{ $colorTotalPP }}; font-variant-numeric: tabular-nums;">
                {{ number_format($totalPasivoPatrimonio, 2) }}
            </div>
        </div>

        {{-- Balanceado --}}
        <div style="border:1px solid #e5e7eb; border-radius:14px; padding:16px 18px; background:white;">
            <div style="font-size:12px; color:#6b7280; margin-bottom:6px;">Balanceado</div>
            <div style="font-size:24px; font-weight:800; color: {{ $colorBalanceado }};">
                {{ $balanceado ? 'Sí' : 'No' }}
            </div>
            <div style="margin-top:6px; font-size:12px; color:#6b7280;">
                Diferencia: <span style="font-variant-numeric: tabular-nums;">{{ number_format($diff, 2) }}</span>
            </div>
        </div>
    </div>
</div>

{{-- DETALLE --}}
<x-filament::section heading="Detalle de cuentas">
    @if (!empty($rows))

        <div style="overflow-x:auto; border:1px solid #e5e7eb; border-radius:14px; background:white;">

            <table style="width:100%; border-collapse:collapse; font-size:14px;">

                <thead>
                    <tr style="background:#f9fafb;">
                        <th style="text-align:left; padding:14px 18px; font-weight:600; color:#374151;">Código</th>
                        <th style="text-align:left; padding:14px 18px; font-weight:600; color:#374151;">Cuenta</th>
                        <th style="text-align:left; padding:14px 18px; font-weight:600; color:#374151;">Clasificación</th>
                        <th style="text-align:right; padding:14px 18px; font-weight:600; color:#374151;">Saldo</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($rows as $r)
                        @php
                            $saldo = $r['saldo'] ?? 0;
                            $saldoColor = $saldo < 0 ? '#dc2626' : '#111827';
                        @endphp

                        <tr style="border-top:1px solid #e5e7eb;"
                            onmouseover="this.style.background='#f9fafb'"
                            onmouseout="this.style.background='transparent'">

                            <td style="padding:14px 18px; white-space:nowrap;">
                                {{ $r['codigo'] ?? '' }}
                            </td>

                            <td style="padding:14px 18px;">
                                {{ $r['nombre'] ?? '' }}
                            </td>

                            <td style="padding:14px 18px;">
                                {{ $r['clasificacion'] ?? '' }}
                            </td>

                            <td style="padding:14px 18px; text-align:right; font-variant-numeric: tabular-nums; color: {{ $saldoColor }};">
                                {{ number_format($saldo, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    @else
        <div style="font-size:14px; color:#6b7280;">No hay movimientos para el período.</div>
    @endif
</x-filament::section>

{{-- Responsivo: si la pantalla es pequeña, que las 3 cards se apilen --}}
<style>
@media (max-width: 900px) {
    .balance-grid-3 {
        grid-template-columns: 1fr !important;
    }
}
</style>