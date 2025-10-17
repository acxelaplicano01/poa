@extends('layouts.guest')

@section('title', isset($exception) ? class_basename($exception) : 'Error')
@section('code', isset($exception) ? $exception->getStatusCode() : 'Error')
@section('message', isset($exception) ? $exception->getMessage() ?: 'Ha ocurrido un error inesperado.' : 'Ha ocurrido un error inesperado.')

@section('action-buttons')
<div class="flex justify-center">
    <a href="{{ url()->previous() }}" 
       class="inline-flex items-center justify-center px-5 py-2 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Volver atrás
    </a>
</div>
@endsection