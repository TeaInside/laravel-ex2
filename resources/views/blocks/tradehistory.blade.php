<h3>{{{ trans('texts.recent_market_history')}}}</h3>

	<div class="btn-default btn-block">
	<div class="scrolltable  nano">
	<div class="nano-content">
	<div id="trade_histories_{{{Session::get('market_id')}}}" class="">
	  <table class="table table-striped table-hover">
		<thead>
		  <tr class="header-tb"><th>{{{ trans('texts.date')}}}</th><th>{{{ trans('texts.type')}}}</th><th>{{{ trans('texts.price')}}} / {{{$coinmain}}}</th><th>{{trans('texts.total')}} {{{ $coinmain }}}</th><th>{{trans('texts.total')}} {{{$coinsecond}}}</th></tr> 
		</thead>
		<tbody>
		  @foreach($trade_history as $history)     
			<tr id="trade-{{$history->id}}" class="order">
			  <td><span>{{$history->created_at}}</span></td><!-- title="26 sec. ago" -->
			  @if($history->type == 'sell')          
				<td><span style="color:red"><strong>{{ ucwords($history->type) }} <i class="fa fa-arrow-down" ></i> </strong></span></td>
			  @else          
				<td><span style="color:green"><strong>{{ ucwords($history->type) }} <i class="fa fa-arrow-up" ></i></strong></span></td>            
			  @endif          
			  <td>{{{sprintf('%.8f',$history->price)}}}</td><td>{{{sprintf('%.8f',$history->amount)+0 }}}</td>
			  <td>{{{ sprintf('%.8f',$history->price*$history->amount) }}}</td>
			</tr> 
		  @endforeach  
		</tbody>
	  </table>
	</div>
	</div>
	</div>
</div>