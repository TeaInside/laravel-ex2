@extends('admin.layouts.master')
@section('content') 

<h2>Add Coin News</h2>
@if ( is_array(Session::get('error')) )
        <div class="alert alert-danger">{{ head(Session::get('error')) }}</div>
    @elseif ( Session::get('error') )
      <div class="alert alert-danger">{{{ Session::get('error') }}}</div>
    @endif
    @if ( Session::get('success') )
      <div class="alert alert-success">{{{ Session::get('success') }}}</div>
    @endif

    @if ( Session::get('notice') )
          <div class="alert alert-info">{{{ Session::get('notice') }}}</div>
    @endif

<form class="form-horizontal" role="form" method="POST" action="{{{ Confide::checkAction('Admin_SettingController@addCoinNews') ?: URL::to('/admin/add-coin-news') }}}" id="add_post">    
    <div class="form-group">
        <label for="market_id" class="col-sm-2 control-label">Market</label>
        <div class="col-sm-10">
            <select class="form-control" name="market_id" id="market_id">
                @foreach ($market_list as $key => $val)
                <option value="{{{$key}}}">{{{$val}}}</option>
                @endforeach
            </select>
        </div>
    </div>  
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Title</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" name="title" id="title">        
        </div>
    </div>  
    <div class="form-group">
        {{--<label for="inputPassword3" class="col-sm-2 control-label">Content</label>--}}
        <div class="col-sm-10">       
          <textarea class="form-control" id="content" name="content" cols="90" rows="10"></textarea>       
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary" id="add_new">{{trans('admin_texts.add')}}</button>
        </div>
    </div>
</form>
{{ HTML::script('assets/js/jquery.validate.min.js') }}
<script type="text/javascript">
$(document).ready(function() {      
        $("#add_post").validate({
            rules: {               
                title: "required",
                content: "required",
            },
            messages: {
                title: "Please provide a title for this article.", 
                content: "Please provide a body for this article.", 
            }
    });

   });
</script>
@stop