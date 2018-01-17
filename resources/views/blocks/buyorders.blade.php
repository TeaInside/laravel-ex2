<div style="color: #fff; background-color: #666 !important;" class="inblock order_header">
  <div class="header-left">
	{{{ trans('texts.buy_orders')}}} <span class="right">{{{ trans('texts.total')}}}: <span id="buyorders_amount_all_{{{Session::get('market_id')}}}"></span> <?php echo $coinmain ?></span>
  </div>
</div>


<div class="btn-default btn-block">
  <img class="orders-loading" src="<?php echo asset('images/loading.gif') ?>" alt="loading" style="display:none">
  
  <div id="orders-b-list">   
	<div class="scrolltable  nano">
	<div class="nano-content">
	
    <div id="orders_buy_{{{Session::get('market_id')}}}" class="clear">
      <table class="table table-striped buyorders" id="buyorders">
        <thead>
          <tr class="header-tb">
			  <th>{{{ trans('texts.price')}}}</th>
			  <th>{{{ $coinmain }}}</th>
			  <th>{{{ $coinsecond }}}</th>
		  </tr> 
        </thead>
        <tbody>           
          <?php $total_amount_buy=0; $total_value_buy=0; $tr_i = 0;?>
          @foreach($buy_orders as $buy_order)
           <?php  
			$tr_i++;
            $total_amount_buy+= $buy_order->total_from_value; 
            $total_value_buy+= $buy_order->total_to_value;
            $price = sprintf('%.8f',$buy_order->price);
            $class_price = str_replace(".", "-", $price);
            $class_price = str_replace(",", "-", $class_price);
           ?>
            @if ( Auth::guest() )
              <tr id="order-{{$buy_order->id}}" class="order price-{{$class_price}}" data-counter="{{$tr_i}}" onclick="use_price(2,<?php echo $buy_order->price ?>,<?php echo $buy_order->total_from_value ?>, this)" data-sort="{{sprintf('%.8f',$buy_order->price)}}">
                <td class="price">{{sprintf('%.8f',$buy_order->price)}}</td>
                <td class="amount">{{{sprintf('%.8f',$buy_order->total_from_value)+0}}}</td>
                <td class="total">{{{sprintf('%.8f',$buy_order->total_to_value)}}}</td>
              </tr>
            @else
              @if ( $buy_order->user_id == Confide::user()->id )
              <!-- style="background-color:#b4d5ff !important;" -->
                <tr id="order-{{$buy_order->id}}" class="order price-{{$class_price}} " data-counter="{{$tr_i}}" onclick="use_price(2,<?php echo $buy_order->price ?>,<?php echo $buy_order->total_from_value ?>, this)" data-sort="{{sprintf('%.8f',$buy_order->price)}}">
                  <td class="price">{{sprintf('%.8f',$buy_order->price)}} <i class="fa fa-star" data-toggle="tooltip" data-placement="top" title="{{ trans('user_texts.your_order') }}"></i></td>
                  <td class="amount">{{{sprintf('%.8f',$buy_order->total_from_value)+0}}}</td>
                  <td class="total">{{{sprintf('%.8f',$buy_order->total_to_value)}}}</td>
                </tr>
              @else
                <tr id="order-{{$buy_order->id}}" class="order price-{{$class_price}}" data-counter="{{$tr_i}}" onclick="use_price(2,<?php echo $buy_order->price ?>,<?php echo $buy_order->total_from_value ?>, this)" data-sort="{{sprintf('%.8f',$buy_order->price)}}">
                  <td class="price">{{sprintf('%.8f',$buy_order->price)}}</td>
                  <td class="amount">{{{sprintf('%.8f',$buy_order->total_from_value)+0}}}</td>
                  <td class="total">{{{sprintf('%.8f',$buy_order->total_to_value)}}}</td>
                </tr>
              @endif
            @endif
          @endforeach 
        </tbody>
      </table>
      </div>
      </div>
      </div>
  </div> 
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#buyorders_amount_all_'+{{{Session::get('market_id')}}}).text( prettyFloat('<?php echo $total_amount_buy ?>', 8));
		$('#buyorders_amount_all_box_'+{{{Session::get('market_id')}}}).text( prettyFloat('<?php echo $total_amount_buy ?>', 1));
	});
</script>