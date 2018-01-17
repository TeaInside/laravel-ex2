<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">

		<!-- Trade History -->
		<?php
		$query_string = '';
		foreach (Request::query() as $key => $value) {
			if($key!='pager_page')
			$query_string .= $key."=".$value."&";
		}
		$query_string = trim($query_string,'&');
		if(!empty($query_string)) $query_string = "&".$query_string;
		?>
		<div id="trade_history">
			<h2>{{{ trans('Trade history')}}} @if(isset($current_coin)) {{' - '.$current_coin}} @endif</h2>
			<form class="form-inline" method="GET" action="{{Request::url()}}">
				@if($filter=='')
					<label>{{{ trans('texts.market')}}}</label>        
					<select id="pair" style="margin-right: 20px;" name="market" class="form-control">
						<option value="" @if(isset($_GET['market']) == '') selected @endif>{{trans('texts.all')}}</option>
							@foreach($markets as $key=> $market)
								<option value="{{$market['id']}}" @if(isset($_GET['market']) && $_GET['market']==$market['id']) selected @endif>{{ strtoupper($market['wallet_from'].'/'.$market['wallet_to'])}}</option>
							@endforeach
					</select>
				@endif
				<label>{{{ trans('texts.type')}}}</label>
				<select id="type" name="type" style="margin-right: 20px;" class="form-control">
					<option value="" @if(isset($_GET['type']) == '') selected @endif>{{trans('texts.all')}}</option>
						<option value="sell" @if(isset($_GET['type']) && $_GET['type'] == 'sell') selected @endif>{{trans('texts.sell')}}</option>
						<option value="buy" @if(isset($_GET['type']) && $_GET['type'] == 'buy') selected @endif>{{trans('texts.buy')}}</option>
				</select>
				<!-- <label>{{{ trans('texts.i_am')}}}</label>
				<select id="type" name="i_am" style="margin-right: 20px;">
					<option value="" selected="selected">{{trans('texts.all')}}</option>
					<option value="seller_id">{{trans('texts.seller')}}</option>
					<option value="buyer_id">{{trans('texts.buyer')}}</option>
				</select> -->
				<button type="submit" class="btn btn-primary" name="do_filter">{{trans('texts.filter')}}</button>
			</form>
			<table class="table table-striped">
				<thead>
				<tr>
					<th>ID</th>
					<th>{{{ trans('texts.market')}}}</th>
					<th>{{{ trans('texts.type')}}}</th>
					<th>{{{ trans('texts.price')}}}</th>
					<th>{{{ trans('texts.amount')}}}</th>
					<th>{{{ trans('texts.total')}}}</th>
					<th>{{{ trans('texts.fee')}}}</th>
					<th>{{{ trans('texts.net_total')}}}</th>
					<th>{{{ trans('texts.date')}}}</th>
				</tr>
				</thead>
				<tbody>
				@foreach($tradehistories as $trade_history)
					@if (isset($markets[$trade_history->market_id]['wallet_from']) && isset($markets[$trade_history->market_id]['wallet_to']))
					<tr id="trade_id_{{$trade_history->id}}">
						<td>{{$trade_history->id}}</td>
						<td>{{$markets[$trade_history->market_id]['wallet_from'].'/'.$markets[$trade_history->market_id]['wallet_to']}}</td>
						@if($trade_history->seller_id == $user_id && $trade_history->buyer_id == $user_id)
						<td><b style="color:green">Buy</b>/<b style="color:red">Sell</b></td>
						@else
							@if($trade_history->seller_id == $user_id)
							<td><b style="color:red">Sell</b></td>
							@else
							<td><b style="color:green">Buy</b></td>
							@endif
						@endif
					   <!-- 
						@if($trade_history->type == 'sell')          
							<td><b style="color:red">{{ ucwords($trade_history->type) }}</b></td>            
						@else          
							<td><b style="color:green">{{ ucwords($trade_history->type) }}</b></td>            
						 @endif -->
						<td>{{sprintf('%.8f',$trade_history->price)}}</td>
						<td>{{sprintf('%.8f',$trade_history->amount)}}</td>
						<td>{{sprintf('%.8f',$trade_history->amount*$trade_history->price)}}</td>
						@if($trade_history->seller_id==$user_id) 
							<td>{{sprintf('%.8f',$trade_history->fee_sell)}}</td>
							<td>{{sprintf('%.8f',($trade_history->amount*$trade_history->price)+$trade_history->fee_sell)}}</td>
						@else 
							<td>{{sprintf('%.8f',$trade_history->fee_buy)}}</td>
							<td>{{sprintf('%.8f',($trade_history->amount*$trade_history->price)+$trade_history->fee_buy)}}</td>
						@endif
						<td>{{$trade_history->created_at}}</td>
					</tr>
					@endif
				@endforeach        
				</tbody>
			</table>
			<div id="pager"></div>
		</div>
		{{ HTML::script('assets/js/bootstrap-paginator.js') }}
		<script type="text/javascript">
		var options = {
				currentPage: <?php echo $cur_page ?>,
				totalPages: <?php echo $total_pages ?>,
				alignment:'right',
				pageUrl: function(type, page, current){
					<?php
					if(empty($filter)){ ?>
					return "<?php echo URL::to('user/profile/trade-history'); ?>"+'?pager_page='+page+'<?php echo $query_string ?>'; 
				<?php }else{ ?> return "<?php echo URL::to('user/profile/trade-history').'/'.$filter; ?>"+'?pager_page='+page+'<?php echo $query_string ?>'; 
				 <?php } ?>
				}
			}
			$('#pager').bootstrapPaginator(options);
			$('#pager').find('ul').addClass('pagination');
		</script>
	</div>
</div>