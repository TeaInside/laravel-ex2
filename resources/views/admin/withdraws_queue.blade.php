@extends('admin.layouts.master')
@section('content')	
{{ HTML::script('assets/js/bootstrap-paginator.js') }}
<h2>{{trans('admin_texts.withdraws_queue')}}</h2>
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
	 	<th>{{trans('admin_texts.fee')}}</th>
	 	<th>{{trans('admin_texts.receive_amount')}}</th>	 	
	 	<th>{{trans('admin_texts.date')}}</th>	 	
	 	<th>{{trans('admin_texts.status')}}</th>
	 	<th>{{trans('admin_texts.action')}}</th>
	</tr> 	
	@foreach($withdraws as $withdraw)
		<tr><td>{{$wallets[$withdraw->wallet_id]['type']}}</td><td>{{$withdraw->username}}</td><td>{{$withdraw->to_address}}</td><td>{{sprintf('%.8f',$withdraw->amount)}}</td><td>{{sprintf('%.8f',$withdraw->fee_amount)}}</td><td>{{sprintf('%.8f',$withdraw->receive_amount)}}</td><td>{{$withdraw->created_at}}</td>
			@if($withdraw->status)
			<td class="status">Approved</td>
			<td><a href="#"></a></td>
			@else
			<td class="status" style="color:red">Wait approve</td>
			<td><a href="#">{{trans('admin_texts.approve')}}</a></td>
			@endif
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
        	return "<?php echo URL::to('admin/manage/withdraws-queue'); ?>"+'/'+page+'<?php echo "?".$query_string ?>'; 
        }
    }
    $('#pager').bootstrapPaginator(options);
</script>
@stop