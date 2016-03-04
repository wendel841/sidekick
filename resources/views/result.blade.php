@extends('layout.main')

@section('content')
	@include('partials.form', ['textBefore' => $textBefore, 'textAfter' => $textAfter])

	<h3 class="page-header">{{ trans('lang.form.label.result') }}</h3>

	<div class="well well-sm">
		{!! $result !!}
	</div>
@endsection