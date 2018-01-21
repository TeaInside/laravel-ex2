@extends('admin.layouts.master')
@section('content') 

<h2>Add Coin Giveaway</h2>
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

<form class="form-horizontal" role="form" method="POST" action="/admin/add-coin-giveaway" id="add_post">    
    <div class="form-group">
		<input type="hidden" name="_token" value="{{{ Session::token() }}}">
        <label for="market_id" class="col-sm-2 control-label">Wallet</label>
        <div class="col-sm-10">
            <select class="form-control" name="wallet_id" id="wallet_id">
                @foreach ($wallet_list as $key => $val)
                <option value="{{{$key}}}">{{{$val}}}</option>
                @endforeach
            </select>
        </div>
    </div>  
    <div class="form-group">
        <label for="amount" class="col-sm-2 control-label">Amount</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" name="amount" id="amount">        
        </div>
    </div>  
    <div class="form-group">
        <label for="time_interval" class="col-sm-2 control-label">Time Interval (hour)</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" name="time_interval" id="time_interval">        
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
            amount: "required",
            content: "required",
        },
        messages: {
            amount: "Please provide an amount.", 
        }
    });
});
</script>
@stop