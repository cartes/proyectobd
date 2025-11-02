@extends('layouts.app')

@section('page-title')
    @if (Auth::user()->isAdmin())
        Panel de Control
    @elseif(Auth::user()->isSugarDaddy())
        Mi Dashboard
    @else
        Mi Espacio
    @endif
@endsection

@section('content')
    @if (Auth::user()->isAdmin())
        @include('dashboard.admin', ['stats' => $stats, 'recentActivity' => $recentActivity])
    @elseif(Auth::user()->isSugarDaddy())
        @include('dashboard.sugar-daddy', ['data' => $dashboardData])
    @else
        @include('dashboard.sugar-baby', ['data' => $dashboardData])
    @endif
@endsection
