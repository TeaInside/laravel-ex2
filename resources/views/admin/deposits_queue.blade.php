@extends('admin.layouts.master')
@section('content')	
{{ HTML::script('assets/js/bootstrap-paginator.js') }}
<h2>{{trans('admin_texts.deposits_queue')}}</h2>
<?php
$query_string = '';
foreach (Request::query() as $key => $value) {
    $query_string .= $key."=".$value."&";
}
$query_string = trim($query_string,'&');
?>
<form class="form-inline"  role="form" id="filter_market" method="get" action="{{Request::url()}}">
	<label>{{{ trans('texts.market')}}}</label>        
    <select name="wallet_id">
	  	<option value="" @if(isset($_GET['wallet_id']) && $_GET['wallet_id']=='') selected @endif>--{{trans('admin_texts.all')}}--</option>
	  	@foreach($wallets as $wallet)
	  		<option value="{{$wallet['id']}}" @if(isset($_GET['wallet_id']) && $_GET['wallet_id']==$wallet['id']) selected @endif>{{$wallet['type']}}</option>
	  	@endforeach
    </select>
    <button type="submit" class="btn btn-primary" name="do_filter">{{trans('texts.filter')}}</button>
</form>
<div id="messages"></div>
<table class="table table-striped" id="list-fees">
	<tr>
	 	<th>{{trans('admin_texts.coin')}}</th>
	 	<th>{{trans('admin_texts.user_id')}}</th>
	 	<th>{{trans('admin_texts.address')}}</th> 	
	 	<th>{{trans('admin_texts.amount')}}</th>
	 	<th>{{trans('admin_texts.confirmations')}}</th>
	 	<th>{{trans('admin_texts.date')}}</th>
	</tr> 	
	@foreach($deposits as $deposit)
		<tr>
			<td>{{$wallets[$deposit->wallet_id]['type']}}</td>
			<td>{{$deposit->username}}</td>
			<td>
			@if($wallets[$deposit->wallet_id]['type']=='CTP') {{$deposit->transaction_id}}
			@else
				{{$deposit->address}}
			@endif
			</td>
			<td>{{$deposit->amount}} {{-- {{sprintf('%.8f',$deposit->amount)}} --}}</td>
			<td>{{$deposit->confirmations}}</td><td>{{$deposit->created_at}}</td>			
		</tr>
	@endforeach
</table>
<div id="pager"></div>
<script type='text/javascript'>
    var options = {
        currentPage: <?php echo $cur_page ?>,
        totalPages: <?php echo $total_pages ?>,
        alignment:'right',
        pageUrl: function(type, page, current){
        	return "<?php echo URL::to('admin/manage/deposits-queue'); ?>"+'/'+page+'<?php echo "?".$query_string ?>'; 
        }
    }
    $('#pager').bootstrapPaginator(options);
</script>
@stop