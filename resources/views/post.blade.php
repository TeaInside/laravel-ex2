@extends('layouts.default')
@section('content')

<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">

		<h2>{{{ $post->title}}}</h2>
		<div class="content-body">
			{{$post->body}}
		</div>
		@stop
	</div>
</div>