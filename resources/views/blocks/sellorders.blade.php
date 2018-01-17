<div style="color: #fff; background-color: #666 !important;" class="inblock order_header">
  <div class="header-left">
	{{{ trans('texts.sell_orders')}}} <span class="right">{{{ trans('texts.total')}}}: <span id="sellorders_total_all_{{{Session::get('market_id')}}}"></span> <?php echo $coinsecond ?></span>
  </div>
</div>

<div class="btn-default btn-block">
  <img class="orders-loading" src="<?php echo asset('images/loading.gif') ?>" alt="loading" style="display:none">
  
  <div id="orders-s-list">
	<div class="scrolltable  nano">
	<div class="nano-content">
    <div id="orders_sell_{{{Session::get('market_id')}}}" class="clear">
      <table class="table table-striped sellorders" id="sellorders">
        <thead>
          <tr class="header-tb">
				<th>{{{ trans('texts.price')}}}</th>
				<th>{{{ $coinmain }}}</th>
				<th>{{{ $coinsecond }}}</th>
			</tr>
        </thead>
        <tbody>
          <?php $total_amount_sell=0;  $total_value_sell=0; $tr_i = 0; 
		  //var_dump($sell_orders);
		  ?>
          @foreach($sell_orders as $sell_order)
           <?php 
			$tr_i++;
            $total_amount_sell+= $sell_order->total_from_value; 
            $total_value_sell+= $sell_order->total_to_value;
            $price = sprintf('%.8f',$sell_order->price);
            $class_price = str_replace(".", "-", $price);
            $class_price = str_replace(",", "-", $class_price);
           ?>
            @if ( Auth::guest() )
              <tr id="order-{{$sell_order->id}}" class="order price-{{$class_price}}" data-counter="{{$tr_i}}" onclick="use_price(1,<?php echo $sell_order->price ?>,<?php echo $sell_order->total_from_value ?>, this)" data-sort="{{{sprintf('%.8f',$sell_order->price)}}}">
                <td class="price">{{{sprintf('%.8f',$sell_order->price)}}}</td>
                <td class="amount">{{{sprintf('%.8f',$sell_order->total_from_value)+0}}}</td>
                <td class="total">{{{sprintf('%.8f',$sell_order->total_to_value)}}}</td>
              </tr>
            @else
              @if ( $sell_order->user_id == Confide::user()->id )
			  <?php /* Logged in users order */ ?>
                <!-- style="background-color:#b4d5ff !important;" -->
                <tr id="order-{{$sell_order->id}}" class="order price-{{$class_price}}" style="background-color:#b4d5ff !important;" data-counter="{{$tr_i}}" onclick="use_price(1,<?php echo $sell_order->price ?>,<?php echo $sell_order->total_from_value ?>, this)" data-sort="{{{sprintf('%.8f',$sell_order->price)}}}">
                  <td class="price">{{{sprintf('%.8f',$sell_order->price)}}} <i class="fa fa-star" data-toggle="tooltip" data-placement="top" title="{{ trans('user_texts.your_order') }}"></i></td>
                  <td class="amount">{{{sprintf('%.8f',$sell_order->total_from_value)+0}}}</td>
                  <td class="total">{{{sprintf('%.8f',$sell_order->total_to_value)}}}</td>
                </tr>
              @else
                <tr id="order-{{$sell_order->id}}" class="order price-{{$class_price}}" data-counter="{{$tr_i}}" onclick="use_price(1,<?php echo $sell_order->price ?>,<?php echo $sell_order->total_from_value ?>, this)" data-sort="{{{sprintf('%.8f',$sell_order->price)}}}">
                  <td class="price">{{{sprintf('%.8f',$sell_order->price)}}}</td>
                  <td class="amount">{{{sprintf('%.8f',$sell_order->total_from_value)+0}}}</td>
                  <td class="total">{{{sprintf('%.8f',$sell_order->total_to_value)}}}</td>
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
		$('#sellorders_total_all_'+{{{Session::get('market_id')}}}).text(  prettyFloat('<?php echo $total_value_sell ?>', 8) );
		$('#sellorders_total_all_box_'+{{{Session::get('market_id')}}}).text(  prettyFloat('<?php echo $total_value_sell ?>', 8) );
	});
</script>