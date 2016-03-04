<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			<label for="text-before">{{ trans('lang.form.label.text_before') }}</label>
			<textarea name="text_before" id="text-before" class="form-control" rows="8"@if ($textBefore) readonly @endif>{{ $textBefore }}</textarea>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label for="text-after">{{ trans('lang.form.label.text_after') }}</label>
			<textarea name="text_after" id="text-after" class="form-control" rows="8"@if ($textAfter) readonly @endif>{{ $textAfter }}</textarea>
		</div>
	</div>
</div>