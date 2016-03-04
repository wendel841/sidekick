@extends('layout.main')

@section('content')
	@include('partials.errors')

	<form method="POST">
		@include('partials.form', ['textBefore' => '', 'textAfter' => ''])
		<button type="submit" class="btn btn-default">Сравнить</button>

		{{ csrf_field() }}
	</form>
@endsection