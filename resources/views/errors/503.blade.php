@extends('layouts.app')

@section('title', 'Error 503')

@section('content')
	@php
		$sErrorMessage = \App\Models\Settings::getValue('maintenance_text');
	@endphp
	<h1 class="error-name">503</h1>
	<h3 class="font-bold">
		{{ !empty($sErrorMessage)?$sErrorMessage:'Сайт временно закрыт на тех. обслуживание' }}
	</h3>

	{{--<div class="error-desc">
		<a href="/">Вернуться на главную</a>
	</div>--}}
@endsection
