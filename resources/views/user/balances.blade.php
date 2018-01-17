	
<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">

		<!-- Balance page -->
		<div id="balances">
			<h2>{{{ trans('texts.account_balances') }}}</h2>
			
			
			<p>
			<div class="left">
				{{{ trans('texts.balance_buttons_tip') }}}
			</div>
			</p>
			
			<p>
			<div class="right checkbox">
				<label for="filterCheckBox"><input tabindex="1" type="checkbox" name="filterCheckBox" id="filterCheckBox" value="1">{{{ trans('texts.hide_zero_balance') }}}</label>
			</div>
			</p>
									  
			<table class="table table-striped" id="table_balance">
				<thead>
					<tr>
						<th>Coin Name</th>
						<th>Code</th>
						<th>Available Balance</th>
						<th>Pending Deposits</th>
						<th>Pending Withdrawals</th>
						<th>Held for Orders</th>
					</tr>
				</thead>
				<tbody>

					@foreach($balances as $balance)
						@if(strtoupper($balance['type'])==strtoupper('ctp') && $disable_points)
							{{-- do nothing --}}
						@else            
						<tr @if($balance['balance']>0 || $balance['deposit_pendding']>0 || $balance['withdraw_pendding']>0 || $balance['held_order']>0) data-balance="1" @else data-balance="0" @endif >
							<td>
								<div class="btn-group">
								  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
									<span class="caret"></span>
									{{$balance['name']}}                            
								  </button>
								  <ul class="dropdown-menu">
									@if(!empty($balance['logo_coin']))                        
										<li><span class="logo"><img width="32" height="32" src="{{asset('')}}/{{$balance['logo_coin']}}" /> <strong>{{$balance['name']}}</strong></span></li>
									@endif
									@if(strtoupper($balance['type'])!=strtoupper('ctp'))
										<li><a href="{{{ URL::to('/user/deposit') }}}/{{$balance['id']}}">{{trans('texts.deposit')}} {{$balance['type']}}</a></li>
										<li><a href="{{{ URL::to('/user/withdraw') }}}/{{$balance['id']}}">{{trans('texts.withdraw')}} {{$balance['type']}}</a></li>
									@endif
									@if(strtoupper($balance['type'])!=strtoupper('ctp'))
									<li><a href="{{{ URL::to('/user/profile/deposits') }}}/{{$balance['id']}}">{{trans('texts.view_deposits_coin',array('coin'=>$balance['type']))}}</a></li>
									@else
									 <li><a href="{{{ URL::to('/user/profile/deposits-point') }}}/{{$balance['id']}}">{{trans('texts.view_deposits_coin',array('coin'=>$balance['type']))}}</a></li>
									@endif
									<li><a href="{{{ URL::to('/user/profile/withdrawals') }}}/{{$balance['id']}}">{{trans('texts.view_withdrawals_coin',array('coin'=>$balance['type']))}}</a></li>
									<li><a href="{{{ URL::to('/user/profile/orders') }}}/{{$balance['id']}}">{{trans('texts.view_orders_coin',array('coin'=>$balance['type']))}}</a></li>
									<li><a href="{{{ URL::to('/user/profile/trade-history') }}}/{{$balance['id']}}">{{trans('texts.view_trades_coin',array('coin'=>$balance['type']))}}</a></li>
									@if(strtoupper($balance['type'])!=strtoupper('ctp') && !empty($balance['download_wallet_client']	))
										<li><a href="{{{ $balance['download_wallet_client'] }}}" target="_blank">{{trans('texts.download_wallet',array('coin'=>$balance['type']))}}</a></li>
									@endif

								  </ul>
								</div>
							</td>
							<td>{{$balance['type']}}</td>
							<td @if($balance['balance']>0) class="has_amount" @endif>{{$balance['balance']}}</td>
							<td @if($balance['deposit_pendding']>0) class="has_amount" @endif>{{$balance['deposit_pendding']}}</td>
							<td @if($balance['withdraw_pendding']>0) class="has_amount" @endif>{{$balance['withdraw_pendding']}}</td>
							<td @if($balance['held_order']>0) class="has_amount" @endif>{{$balance['held_order']}}</td>
						</tr>
						
						@endif
					@endforeach
				</tbody>
			</table>
			
		</div>

		<script type="text/javascript" charset="utf-8">	
			
			$(function() {
				$("#filterCheckBox").on('click', function() {
					$("#table_balance tr[data-balance='0']").toggle();
				});
			});



		</script>
	</div>
</div>