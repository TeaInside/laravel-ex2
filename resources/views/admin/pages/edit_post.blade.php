@extends('admin.layouts.master')
@section('content')	

<h2>{{trans('admin_texts.edit')}} {{$post->type}}</h2>
@if ( is_array(Session::get('error')) )
        <div class="alert alert-error">{{ head(Session::get('error')) }}</div>
	@elseif ( Session::get('error') )
      <div class="alert alert-error">{{{ Session::get('error') }}}</div>
	@endif
	@if ( Session::get('success') )
      <div class="alert alert-success">{{{ Session::get('success') }}}</div>
	@endif

	@if ( Session::get('notice') )
	      <div class="alert">{{{ Session::get('notice') }}}</div>
	@endif

<form class="form-horizontal" role="form" method="POST" action="{{{ Auth::check('admin\\AdminSettingController@doEditPost') ?: URL::to('/admin/edit-post') }}}" id="add_post">	
	<div class="form-group">
	    <p><label for="title" >{{trans('admin_texts.title')}}</label></p>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" name="title" id="title" value="{{$post->title}}" style="width: 400px;" />
	    </div>
	</div>	
	<div class="form-group">	    
	    <div class="col-sm-10">	      
	      <textarea class="form-control" id="body" name="body" cols="90" rows="10">{{$post->body}}</textarea>	     
	    </div>
	</div>
	<div class="form-group">	    
	    <div class="col-sm-10">	      
			<p><label for="permalink" >{{trans('admin_texts.permalink')}}</label></p>
			<div class="col-sm-10">
				<input type="text" class="form-control" name="permalink" id="permalink" value="{{$post->permalink}}" style="width: 400px;" />
			</div>
	      
	    </div>
	</div>
	
	<div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10">
		      <div class="checkbox">
		        <label>
		          <input type="checkbox" id="show_menu" name="show_menu" value="1" @if($post->show_menu) checked @endif> Show on menu
		        </label>
		      </div>      
	    </div>
  	</div> 
	<div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10">
	    	<input type="hidden" class="form-control" name="type" id="type" value="{{$post->type}}">
	    	<input type="hidden" class="form-control" name="post_id" id="post_id" value="{{$post->id}}">
	    	
	      <button type="submit" class="btn btn-primary" id="add_new">{{trans('admin_texts.save')}}</button>
	    </div>
	</div>
</form>
{{ HTML::script('assets/js/jquery.validate.min.js') }}
<script type="text/javascript">
$(document).ready(function() {    	
        $("#add_post").validate({
            rules: {               
                title: "required",
                body: "required",
            },
            messages: {
                title: "Please provide a title for this article.", 
                body: "Please provide a body for this article.", 
            }
	});

   });
</script>
@stop