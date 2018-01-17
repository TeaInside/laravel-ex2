@extends('layouts.default')
@section('content')

<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">

	
		<h2>Coin Voting</h2>
		Please vote for the coins that you want added to {{{ Config::get('config_custom.company_name') }}}. All members with a trade history may have 2 votes per day. <br>
		You may also buy votes. Each vote costs 0.001 BTC. 

		<BR><br>If you would like to suggest a coin for the voting system, please submit it <a href=https://sweedx.com/page/submit-coin>here</a>.


		<br><br>
		<table class="table table-striped table-hover">
			<thead>
			  <tr>
				  <th>{{{ trans('texts.coin_code')}}}</th>
				  <th>{{{ trans('texts.coin_name')}}}</th>
				  <th>{{{ trans('texts.btc_payment_address')}}}</th>
				  <th>Votes</th>              
			  </tr>
			</thead>
			<tbody>        
				@foreach($coinvotes as $coinvote)
				<tr>
					<td>{{$coinvote->code}}</td>
					<td>{{$coinvote->name}}</td>
					<td><a href="https://blockchain.info/address/{{$coinvote->btc_address}}" target="_blank">{{$coinvote->btc_address}}</a></td>
					<td><span id="numvote_{{$coinvote->id}}">@if(isset($coinvote->num_vote)) {{$coinvote->num_vote}} @else 0 @endif</span> @if ( !Auth::guest() ) <button name="vote_now" onclick="voteNow({{$coinvote->id}})" class="btn btn-primary vote_now">Vote Now</button> @endif</td>
				</tr>
				@endforeach
			</tbody>
		</table>

		<script type="text/javascript">
		function voteNow(coinvote_id){         

			$.ajax({
				type: 'post',
				url: '<?php echo action('VoteCoinController@doVoting')?>',
				datatype: 'json',
				data: {isAjax: 1, coinvote_id: coinvote_id },
				beforeSend: function(request) {
					return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
				},
				success:function(response) {
					var obj = $.parseJSON(response);
					console.log('obj: ',obj);
					var title = 'Coin voting';
					var msg = obj.message;
					
					var coin_count = parseInt( $('#numvote_'+obj.coinvote_id).text() );
					$('#numvote_'+obj.coinvote_id).text(coin_count+1);
					
					BootstrapDialog.show({
						title: title,
						message: msg
					});
				}, error:function(response) {
					showMessageSingle('{{{ trans('texts.error') }}}', 'error');
				}
			});
					
			  /*
			  $.post('<?php echo action('VoteCoinController@doVoting')?>', {isAjax: 1, coinvote_id: coinvote_id}, function(response){
				  var obj = $.parseJSON(response);
				  console.log('obj: ',obj);
				  var title = 'Coin voting';
				  var msg = '';

				  msg = obj.message;
					BootstrapDialog.show({
						title: title,
						message: msg
					});
			  });
			  */
			  return false;
		}
		</script>
		@stop
	</div>
</div>