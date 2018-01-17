<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">

			<!-- Security -->
		<div id="coin_deposits">	
			<h2>{{{ trans('texts.coin_deposits')}}} @if(isset($current_coin)) {{' - '.$current_coin}} @endif</h2>    
			Below is a list of deposits that you have made.
			<br><br>
			<span class="text-high">To make a new deposit, please visit the {{ HTML::link('user/profile/balances', trans('user_texts.balance')) }} page and select the Deposit option under the actions menu for the coin.</span>
			<br><br>
			@if ( Session::get('error') )
				<div class="alert alert-error alert-danger">
					@if ( is_array(Session::get('error')) )
						{{ head(Session::get('error')) }}
					@else
						{{Session::get('error')}}
					@endif
				</div>
			@endif

			@if ( Session::get('notice') )
				<div class="alert">{{ Session::get('notice') }}</div>
			@endif
			<form class="form-inline" method="POST" action="{{Request::url()}}">
				<input type="hidden" name="_token" id="_token" value="{{{ Session::token() }}}">
				@if($filter=='')
					<label>{{{ trans('texts.coin')}}}</label>        
					<select id="pair" style="margin-right: 20px;" name="wallet" class="form-control">
						<option value="" selected="selected">{{trans('texts.all')}}</option>
						@foreach($wallets as $key=> $wallet)
							<option value="{{$wallet['id']}}">{{$wallet->type}}</option>
						@endforeach
					</select>
				@endif
				<label>{{{ trans('texts.type')}}}</label>
				<select id="type" name="status" style="margin-right: 20px;" class="form-control">
					<option value="" selected="selected">{{trans('texts.all')}}</option>
					<option value="0">{{trans('texts.pending')}}</option>
					<option value="1">{{trans('texts.complete')}}</option>
				</select>        
				<button type="submit" class="btn btn-primary" name="do_filter">{{trans('texts.filter')}}</button>
			</form>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>{{{ trans('texts.date')}}}</th>
						<th>{{{ trans('texts.coin')}}}</th>
						<th>{{{ trans('texts.amount')}}}</th>
						<th>{{{ trans('texts.sending_address')}}}</th>
						<th>{{{ trans('texts.confirmations')}}}</th>
						<th>{{{ trans('texts.status')}}}</th>	            
					</tr>
				</thead>
				<tbody>
					@foreach($deposits as $deposit)
						@if ($deposit->type != 'CTP' || (isset($_REQUEST['wallet']) && $_REQUEST['wallet'] == 13))
						<tr>
							<td>{{$deposit->created_at}}</td>
							<td>{{$deposit->type}}</td>
							<td>{{sprintf('%.8f',$deposit->amount)}}</td>
							<td>{{$deposit->address}}</td>
							<td>{{$deposit->confirmations}}</td>
							@if($deposit->paid)          
								<td><b style="color:green">{{ ucwords(trans('texts.complete')) }}</b></td>  
							@else  
								<td><b style="color:red">{{ ucwords(trans('texts.pending')) }}</b></td> 
							@endif	        		
						</tr>
						<tr><td align="center" colspan="6">TrxID: {{$deposit->transaction_id}}</td></tr>
						@endif
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
