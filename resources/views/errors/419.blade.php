@extends('layouts.guest')

@section('title', 'Página expirada')
@section('code', '419')
@section('message', 'Tu sesión ha expirado. Por favor, actualiza la página e intenta de nuevo.')

@section('action-buttons')
<div class="flex justify-center">
    <a href="{{ url()->current() }}" 
       class="inline-flex items-center justify-center px-5 py-2 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        Actualizar página
    </a>
</div>
@endsection