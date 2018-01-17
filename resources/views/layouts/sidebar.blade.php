<?php

//Required Classes

$trade = new Trade();

?>
<!-- Sidebar -->
	<div class="sidebar">
		<div class="list-group">
			<div class="panel-group" id="accordion">
				<div class="sidebar_search" >
					<i class="fa fa-search fa-lg" id="sidebar_search_icon"></i>
					<input type="search" class="form-control" placeholder="{{ trans('texts.search_market')}}" id="sidebar_search_market" />
				</div>
				<div class="panel-collapse collapse in" id="onlineUsers">
				  <div class="panel-body">
						
						<ul class="market well stats">
							<li>
							{{{ trans('texts.online_clients')}}}: <span id="client_count"></span>
							</li>
						</ul>
						
				  </div>
				</div>
				@if(isset($available_balances))
				  <div class="panel panel-default">
					<div class="panel-heading">
					  
						<a data-toggle="collapse"  href="#collapseAvailableBalance">
							<h4 class="panel-title">
							<span class="glyphicon glyphicon-minus"></span>
							  Available Balances
							</h4>
						</a>
					  
					</div>
					<div id="collapseAvailableBalance" class="panel-collapse collapse in">
					  <div class="panel-body">
							<div class="balance nano clear">
								<ul class="market well nano-content">
									<li class="title">
										<span class="name">Coin</span>
										<span class="price">Amount</span>			
									</li>
									<?php
									$ib = 0?>
									@foreach($available_balances as $key=>$available_balance)	
										@if(floatval($available_balance['balance'])>0)	
										<?php
										//var_dump($available_balance);
										?>
										<li>
											<?php
												// Dont not link BTC or LTC itself
											if ($available_balance['type'] === 'BTC' || $available_balance['type'] === 'LTC') :?>
												<a href="#" class="maincoin_available">
													<span class="name">{{$available_balance['type']}}</span><span class="price" id="spanBalance-{{$key}}">{{sprintf('%.8f',$available_balance['balance'])}}</span>
												</a>

											<?php
											else:?>
												<a href="{{{ URL::to('/market/') }}}/{{$available_balance['market_id']}}" >
													<span class="name">{{$available_balance['type']}}</span><span class="price" id="spanBalance-{{$key}}">{{sprintf('%.8f',$available_balance['balance'])}}</span>
												</a>					
											<?php
											endif;
											?>
										</li>
										<?php $ib++?>
										@endif
										
									@endforeach
									<?php 
									if($ib==0){
									?>
										<li>
											<a href="#">
												<span class="empty_balance">Your balance is empty.</span>
											</a>
										</li>
									<?php
									}
									?>
								</ul>
							</div>
					  </div>
					</div>
				  </div>
				  @endif
			  <div class="panel panel-default">
				<div class="panel-heading">
				  <a data-toggle="collapse" href="#collapseBTCMarket">
					<h4 class="panel-title">
						<span class="glyphicon glyphicon-minus"></span>
							BTC Markets
					</h4>
				  </a>
				</div>
				<div id="collapseBTCMarket" class="panel-collapse collapse in">
				  <div class="panel-body">
					<div class="btc_market coinmarket nano clear">
			<?php
			//var_dump($btc_datainfo);
			//var_dump($btc_markets); 
			//$btc_markets = [];
			//$ltc_markets = [];
			?>
			
					<ul class="market well nano-content">
							<li class="title">
								<span class="name">Coin</span>
								<span class="price">Price</span>
								<span class="change">% Change</span>
							</li>

							@foreach($btc_markets as $btc_market)
							<?php 
								$total_btc = isset($btc_datainfo[$btc_market->id]['total'])? $btc_datainfo[$btc_market->id]['total']:0; 
								$curr_price = isset($btc_datainfo[$btc_market->id][0]['price'])? $btc_datainfo[$btc_market->id][0]['price']:0;
								$pre_price = isset($btc_datainfo[$btc_market->id][1]['price'])? $btc_datainfo[$btc_market->id][1]['price']:0;
								$change = 0;
								//$change = ($pre_price!=0) ? sprintf('%.2f',(($curr_price-$pre_price)/$pre_price)*100) : 0;
								//$change = $change +0;
								
								if ( isset($btc_datainfo[$btc_market->id][1]['created_at']) ){
									$pre_price = $trade->getChangeDayPrevPrice($btc_datainfo[$btc_market->id][1]['created_at'], $pre_price);
								}
									$change = $trade->getChangeDayPrice($pre_price, $curr_price, $pre_price);


								
								//echo "Cur: ".$curr_price." -- Pre: ".$pre_price;
								//if($change>0) $change = '+'.$change;
								
								
							?>
								<li class="volume" id="volume-{{$btc_market->id}}" data-toggle="tooltip" data-placement="right" title="Vol: {{sprintf('%.8f',$total_btc)}} BTC">
									<a href="{{{ URL::to('/market/') }}}/{{$btc_market->id}}">
										<span class="name">
											@if($btc_market->enable_trading == 0) <i class="fa fa-exclamation-triangle red" data-toggle="tooltip" data-placement="bottom" title="{{$btc_market->type}} - {{ trans('texts.market_disabled') }}" ></i> @endif
											{{$btc_market->type}}
										</span>
										<span class="price" yesterdayPrice="{{sprintf('%.8f',$pre_price)}}" id="spanPrice-{{$btc_market->id}}">{{sprintf('%.8f',$curr_price)}}</span>
											@if($change==0)
												<span class="change" id="spanChange-{{$btc_market->id}}">{{$change}}% <i class="fa fa-minus"></i></span>
											@elseif($change>0)
												<span class="change up" id="spanChange-{{$btc_market->id}}">{{$change}}% <i class="fa fa-arrow-up"></i></span>
											@else
												<span class="change down" id="spanChange-{{$btc_market->id}}">{{$change}}% <i class="fa fa-arrow-down"></i></span>
											@endif
									</a>
								<?php /* <div class="volume" id="volume-{{$btc_market->id}}" data-toggle="tooltip" data-placement="right" title="sdasd">Vol: {{sprintf('%.8f',$total_btc)}} BTC</div> */?>
								</li> 
							@endforeach
						</ul>
						</div>
						
				  </div>
				</div>
			  </div>
			  
			  
			  <div class="panel panel-default">
				<div class="panel-heading">
				  
					<a data-toggle="collapse"  href="#collapseLTCMarket">
						<h4 class="panel-title">
						<span class="glyphicon glyphicon-minus"></span>
						  LTC Markets
						</h4>
					</a>
				  
				</div>
				<div id="collapseLTCMarket" class="panel-collapse collapse in">
				  <div class="panel-body">
						<div class="ltc_market coinmarket nano clear">
							<ul class="market well nano-content">
								<li class="title">
									<span class="name">Coin</span>
									<span class="price">Price</span>
									<span class="change">% Change</span>
								</li>
								@foreach($ltc_markets as $ltc_market)
								<?php 
									$total_ltc = isset($ltc_datainfo[$ltc_market->id]['total'])? $ltc_datainfo[$ltc_market->id]['total']:0; 
									$curr_price = isset($ltc_datainfo[$ltc_market->id][0]['price'])? $ltc_datainfo[$ltc_market->id][0]['price']:0;
									$pre_price = isset($ltc_datainfo[$ltc_market->id][1]['price'])? $ltc_datainfo[$ltc_market->id][1]['price']:0;
									
									//if($change>0) $change = '+'.$change;
									
									if ( isset($ltc_datainfo[$ltc_market->id][1]['created_at']) ){
										$pre_price = $trade->getChangeDayPrevPrice($ltc_datainfo[$ltc_market->id][1]['created_at'], $pre_price);
									}
									$change = $trade->getChangeDayPrice($pre_price, $curr_price, $pre_price);
									
									
									
									/*
									if ( isset($ltc_datainfo[$ltc_market->id][1]['created_at']) ) {
										//Check previous trade date and compare to the previous day
										if ( strtotime($ltc_datainfo[$ltc_market->id][1]['created_at']) < strtotime('yesterday') )
											$pre_price = 0;
									}
									$change = ($pre_price!=0)? sprintf('%.2f',(($curr_price-$pre_price)/$pre_price)*100) : 0;
									$change = $change +0;
									*/
									
									/*
									echo '<pre>';
									print_r($ltc_datainfo[$ltc_market->id]);
									echo '</pre>';
									*/
								?>
									<li class="volume" id="volume-{{$ltc_market->id}}" data-toggle="tooltip" data-placement="right" title="Vol: {{sprintf('%.8f',$total_ltc)}} LTC">
										<a href="{{{ URL::to('/market') }}}/{{$ltc_market->id}}">
										<span class="name">
											@if($ltc_market->enable_trading == 0) <i class="fa fa-exclamation-triangle red" data-toggle="tooltip" data-placement="bottom" title="{{$ltc_market->type}} - {{ trans('texts.market_disabled') }}" ></i> @endif
											{{$ltc_market->type}}
										</span>
										<span class="price" yesterdayPrice="{{sprintf('%.8f',$pre_price)}}" id="spanPrice-{{$ltc_market->id}}">{{sprintf('%.8f',$curr_price)}}</span>
										
											@if($change==0)
												<span class="change" id="spanChange-{{$ltc_market->id}}">{{$change}}% <i class="fa fa-minus"></i></span>
											@elseif($change>0)
												<span class="change up" id="spanChange-{{$ltc_market->id}}">{{$change}}% <i class="fa fa-arrow-up"></i></span>
											@else
												<span class="change down" id="spanChange-{{$ltc_market->id}}">{{$change}}% <i class="fa fa-arrow-down"></i></span>
											@endif
										</a>
										<?php /*<div class="volume" id="volume-{{$ltc_market->id}}" >Vol: {{sprintf('%.8f',$total_ltc)}} LTC</div> */?>
									</li>
								@endforeach
							</ul>
						</div>
				  </div>
				</div>
			  </div>
			  
			  <div class="panel panel-default">
				<div class="panel-heading">
				  
					<a data-toggle="collapse"  href="#collapse24hStatistics">
						<h4 class="panel-title">
						<span class="glyphicon glyphicon-minus"></span>
						  24 Hour Statistics
						</h4>
					</a>
				  
				</div>
				<div id="collapse24hStatistics" class="panel-collapse collapse in">
				  <div class="panel-body">
						
						<ul class="market well stats">
							<?php
							$number_btc = isset($statistic_btc->number_trade)? $statistic_btc->number_trade:0;
							$volume_btc = (isset($statistic_btc->total) && !empty($statistic_btc->total))? sprintf('%.8f',$statistic_btc->total):0;
							$number_ltc = isset($statistic_ltc->number_trade)? sprintf('%.8f',$statistic_ltc->number_trade):0;
							$volume_ltc = (isset($statistic_ltc->total) && !empty($statistic_ltc->total))? sprintf('%.8f',$statistic_ltc->total):0;
							?>
						<li>BTC Volume <span class="change">{{$volume_btc}} BTC</span></li><li>LTC Volume <span class="change">{{$volume_ltc}} LTC</span></li><li>Number of Trades <span class="change">{{$number_ltc+$number_btc}}</span></li> </ul>
				  </div>
				</div>
				
			  </div>
			  

			  <div class="panel panel-default">
				<div class="panel-heading">
				  
					<a data-toggle="collapse"  href="#supportBase">
						<h4 class="panel-title">
						<span class="glyphicon glyphicon-minus"></span>
						  Support/Feedback
						</h4>
					</a>
				  
				</div>
				<div id="supportBase" class="panel-collapse collapse in">
				  <div class="panel-body">
						
						<ul class="market well stats">
							<li>
							<a href="mailto:<?php echo Config::get('config_custom.company_support_mail')?>"><?php echo Config::get('config_custom.company_support_mail')?></a>
							</li>
						</ul>
						
				  </div>
				</div>
				
			  </div>
				
				<?php
				/*
				<div id="twitter-feed">
					<a class="twitter-timeline" href="https://twitter.com/Sweedx_com" data-widget-id="533686062652997632">Tweets by @Sweedx_com</a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

				</div>
				*/?>
				
			  
			</div>


				
						

					
					<br />
					
					
						<?php
						/*
						<script type="text/javascript" src="https://www4.yourshoutbox.com/shoutbox/start.php?key=505247175"></script>
						

						
						<br />

						
						*/
						?>
				
				
				
				<br/><br/>
		</div>        
	</div>

<!-- /#sidebar-wrapper -->