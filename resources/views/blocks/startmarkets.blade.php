

<!-- https://www.freeformatter.com/html-formatter.html#ad-output -->
<!-- https://dirtymarkup.com/ -->
<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">
		<h2 id="nav-pills" style="float: left; margin-top:0px;">BTC - Live Market Data</h2>
		<br />
		<?php
			//var_dump($all_markets);
			?>
		<hr class="colorgraph"/>
		<table class="table table-striped table-hover market market_table bootstrap-popup" id="btc_market_table">
			<thead>
				<tr class="header-tb">
					<th data-priority="4">Currency</th>
					<th data-priority="critical">Market</th>
					<th data-priority="critical">Last Price</th>
					<th data-priority="1">% Change</th>
					<th data-priority="2">24H High</th>
					<th data-priority="3">24H Low</th>
					<th data-priority="critical">24H Volume</th>
				</tr>
			</thead>
			<tbody>
				<?php
					//var_dump($all_markets);
					?>
				@foreach($all_markets as $am)
				@if ($am['to'] == 'BTC')
				<tr id="mainCoin-{{$am['market']->id}}">
					<td class="from_name">
						@if(!empty($am['logo']))                        
						<a href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}"><img src="{{asset('')}}/{{$am['logo']}}" class="coin_icon_small" /></a>
						@else
						&nbsp;
						@endif
						<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['from_name']}}</a>
					</td>
					<td>
						<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">
						@if($am['enable_trading'] == 0) <i class="fa fa-exclamation-triangle red" data-toggle="tooltip" data-placement="bottom" title="{{$am['from_name']}} - {{ trans('texts.market_disabled') }}" ></i> @endif
						{{$am['from']}}/{{$am['to']}}
						</a>
					</td>
					<td><a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainLastPrice-{{$am['market']->id}}">{{$am['latest_price']}}</a></td>
					<td class="market_change">
						<?php
							

							if (isset($am['prices']->max)){
								if ( sprintf('%.8f',$am['prices']->max)+0 == 0 )
									$coin_max_ = '';
								else
									$coin_max_ = sprintf('%.8f',$am['prices']->max);
								
								if ( sprintf('%.8f',$am['prices']->min)+0 == 0 )
									$coin_min_ = '';
								else
									$coin_min_ = sprintf('%.8f',$am['prices']->min);
							}
							
							
							/*
							echo '<pre>';
							print_r($am);
							echo '</pre>';
							*/
							?>
						@if ($am['market_change'] == 0)
						<span class="change" >{{$am['market_change']}}% <i class="fa fa-minus"></i></span>
						@elseif ($am['market_change'] > 0)
						<span class="change up" >{{$am['market_change']}}% <i class="fa fa-arrow-up"></i></span>
						@else ($am['market_change'] < 0)
						<span class="change down" >{{$am['market_change']}}% <i class="fa fa-arrow-down"></i></span>
						@endif
					</td>
					<td>
						<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainHighPrice-{{$am['market']->id}}">@if(empty($coin_max_)) - @else {{$coin_max_}} @endif</a>
					</td>
					<td>
						<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainLowPrice-{{$am['market']->id}}">@if(empty($coin_min_)) - @else {{$coin_min_}} @endif</a>
					</td>
					<td>
						<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainVolume-{{$am['market']->id}}">@if(empty($am['prices']->volume)) {{{sprintf('%.8f',0)}}} {{$am['to']}} @else {{sprintf('%.8f',$am['prices']->volume)}} {{$am['to']}} @endif</a>
					</td>
				</tr>
				@endif
				@endforeach
			</tbody>
		</table>
		<h2 id="nav-pills" style="float: left; margin-top:0px;">LTC - Live Market Data</h2>
		<table  class="table table-striped table-hover market market_table" id="ltc_market_table">
			<thead class="columnSelector-disable">
				<tr class="header-tb">
					<th data-priority="4">Currency</th>
					<th data-priority="critical">Market</th>
					<th data-priority="critical">Last Price</th>
					<th data-priority="1">% Change</th>
					<th data-priority="2">24 H High</th>
					<th data-priority="3">24 H Low</th>
					<th data-priority="critical">24 H Volume</th>
				</tr>
			</thead>
			<tbody>
				@foreach($all_markets as $am)
				@if ($am['to'] != 'BTC')
				<tr id="mainCoin-{{$am['market']->id}}">
					<td class="from_name">
						@if(!empty($am['logo']))                        
						<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}"><img class="coin_icon_small" src="{{asset('')}}/{{$am['logo']}}" /></a>
						@else
						&nbsp;
						@endif
						<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['from_name']}}</a>
					</td>
					<td>
						@if($am['enable_trading'] == 0) <i class="fa fa-exclamation-triangle red" data-toggle="tooltip" data-placement="bottom" title="{{$am['from_name']}} - {{ trans('texts.market_disabled') }}" ></i> @endif
						<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}">{{$am['from']}}/{{$am['to']}}</a>
					</td>
					<td><a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainLastPrice-{{$am['market']->id}}"> {{$am['latest_price']}}</a></td>
					<?php
						
						if ( sprintf('%.8f',$am['prices']->max)+0 == 0 )
							$coin_max_ = '';
						else
							$coin_max_ = sprintf('%.8f',$am['prices']->max);
						
						if ( sprintf('%.8f',$am['prices']->min)+0 == 0 )
							$coin_min_ = '';
						else
							$coin_min_ = sprintf('%.8f',$am['prices']->min);
						
						?>
					<td class="market_change">
						@if ($am['market_change'] == 0)
						<span class="change" >{{$am['market_change']}}% <i class="fa fa-minus"></i></span>
						@elseif ($am['market_change'] > 0)
						<span class="change up" >{{$am['market_change']}}% <i class="fa fa-arrow-up"></i></span>
						@else ($am['market_change'] < 0)
						<span class="change down" >{{$am['market_change']}}% <i class="fa fa-arrow-down"></i></span>
						@endif
						<?php
							/*
							@if ($am['market_change']['change'] == 0)
								<span class="change" >{{$am['market_change']['change']}}% <i class="fa fa-minus"></i></span>
							@elseif ($am['market_change']['change'] > 0)
								<span class="change up" >{{$am['market_change']['change']}}% <i class="fa fa-arrow-up"></i></span>
							@else ($am['latest_price'] < 0)
								<span class="change down" >{{$am['market_change']['change']}}% <i class="fa fa-arrow-down"></i></span>
							@endif
							*/
							?>
					</td>
					<td>
						<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainHighPrice-{{$am['market']->id}}"> @if(empty($coin_max_)) - @else {{$coin_max_}} @endif</a>
					</td>
					<td>
						<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainLowPrice-{{$am['market']->id}}"> @if(empty($coin_min_)) - @else {{$coin_min_}} @endif</a>
					</td>
					<td>
						<a  href="{{{ URL::to('/market/') }}}/{{$am['market']->id}}" class="nostrong" id="mainVolume-{{$am['market']->id}}"> @if(empty($am['prices']->volume)) {{{sprintf('%.8f',0)}}} {{$am['to']}} @else {{sprintf('%.8f',$am['prices']->volume)}} {{$am['to']}} @endif</a>
					</td>
				</tr>
				@endif
				@endforeach
			</tbody>
		</table>
	</div>
</div>

