@extends('layouts.app')

@section('title', 'Admin Panel | Register')

@section('content')
	<div>
		<h1 class="logo-name">{{ config('app.name') }}</h1>
	</div>
	@if (count($errors) > 0)
		<div class="alert alert-danger" role="alert">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
	<h3>Регистрация в системе</h3>
	<form class="m-t" role="form" action="{{ route('register') }}" method="post">
		{{ csrf_field() }}

		<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
			<input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Логин" required="">
			<span class="help-block">
				<strong>{{ $errors->first('name') }}</strong>
			</span>
		</div>

		<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
			<input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required="">
			<span class="help-block">
				<strong>{{ $errors->first('email') }}</strong>
			</span>
		</div>
		<div class="form-group">
			<input type="password" class="form-control" name="password" placeholder="Пароль" required="">
		</div>
		<div class="form-group">
			<input type="password" class="form-control" name="password_confirmation" placeholder="Повторите пароль" required="">
		</div>

		<button type="submit" class="btn btn-primary block full-width m-b">Зарегистрироваться</button>

		<p class="text-muted text-center"><small>Уже есть аккаунт?</small></p>
		<a class="btn btn-sm btn-white btn-block" href="{{ route('login') }}">Войти</a>
	</form>
@endsection
