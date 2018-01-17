<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">

		<!-- Deposit -->
		<div id="coin_deposits">
			<h2>POINTS</h2>    
			Below is the history you were rewarded points.
			<br><br>		
			<table class="table table-striped">
				<tbody>
					<tr>
						<th>{{{ trans('texts.date')}}}</th>
						<th>{{{ trans('texts.coin')}}}</th>
						<th>{{{ trans('texts.description')}}}</th>     
						<th>{{{ trans('texts.amount')}}}</th>	               
					</tr>
					@foreach($deposits as $deposit)
						<tr>
							<td>{{$deposit->created_at}}</td>
							<td>{{$deposit->type}}</td>
							<td>{{$deposit->transaction_id}}</td>	
							<td>{{sprintf('%.8f',$deposit->amount)}}</td>	        		        		     		
						</tr>	        	
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>