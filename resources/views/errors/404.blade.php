@extends('layouts.app')

@section('title', 'Error 404')

@section('content')
	<h1 class="error-name">404</h1>
	<h3 class="font-bold">Страница не найдена</h3>

	<div class="error-desc">
		<a href="/">Вернуться на главную</a>
	</div>
@endsection
