<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">

		<!-- Depost -->
		<div id="form_deposit">
			<div style="color:red">@if(isset($error_message)) {{$error_message}} @endif</div>
			<h2>{{{ trans('texts.deposit')}}} - {{$current_coin}}</h2> 
			Your current available {{$current_coin}} balance: <strong>{{$balance}}</strong>    
			@if($wallet->enable_deposit)
				<h3>Your Deposit Address</h3>
					<div class="addressSection">
						<div class="options box" style="display: inline-block;"><span id="address">{{$address_deposit}}</span> &nbsp; <input type="button" id="copy-button" class="inline" data-clipboard-target="address" value="Copy"></div>
						<?php
						function generateQRwithGoogle($url,$widthHeight ='150',$EC_level='L',$margin='0') {
							$url = urlencode($url); 
							echo '<img src="http://chart.apis.google.com/chart?chs='.$widthHeight.
						'x'.$widthHeight.'&cht=qr&chld='.$EC_level.'|'.$margin.
						'&chl='.$url.'" alt="QR code" widthHeight="'.$widthHeight.
						'" widthHeight="'.$widthHeight.'"/>';
						}
						$urlToEncode=$address_deposit;
						generateQRwithGoogle($urlToEncode);
						
						?>
						<p>You may also use previously generated deposit addresses.</p>
						<br><span id="s_message"></span>
						<br><input class="generateAddress" type="button" value="Generate New Deposit Address" onclick="generateNewAddrDeposit()">

					</div>
					<input type="hidden" name="wallet_id" id="wallet_id" value="{{$wallet_id}}">
					{{ HTML::script('assets/zeroclipboard/ZeroClipboard.min.js') }}
				   <script type="text/javascript">
						var client = new ZeroClipboard(document.getElementById("copy-button"), {
							moviePath: "{{asset('assets/js/zeroclipboard/zeroclipboard.swf')}}"
						});

						client.on( "load", function(client) {

							client.on( "complete", function(client, args) {
							  // `this` is the element that was clicked
							  $(this).css("background", "#666");
							  $(this).val("Copied");
							});
						});
						
						function generateNewAddrDeposit(){
							var wallet_id = $('#wallet_id').val();    
							$.ajax({
								type: 'post',
								url: '<?php echo action('DepositController@generateNewAddrDeposit')?>',
								datatype: 'json',
								data: {isAjax: 1, wallet_id: wallet_id },
								beforeSend: function(request) {
									return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
								},
								success:function(response) {
									var obj = $.parseJSON(response);
								  console.log('ajVerifyToken: ',obj);
								  if(obj.status == 'success'){                  
									 $('#address').html(obj.address);  
								  }else {
									$('#s_message').html('<p style="color:red; font-weight:bold;text-align:center;">'+obj.message+'</p>');
								  }
								}, error:function(response) {
									showMessageSingle('{{{ trans('texts.error') }}}', 'error');
								}
							});
							return false;
						}
						
						/*
						function generateNewAddrDeposit(){
						  var wallet_id = $('#wallet_id').val();    
						  $.post('<?php echo action('DepositController@generateNewAddrDeposit')?>', {isAjax: 1, wallet_id: wallet_id}, function(response){
							  var obj = $.parseJSON(response);
							  console.log('ajVerifyToken: ',obj);
							  if(obj.status == 'success'){                  
								 $('#address').html(obj.address);  
							  }else {
								$('#s_message').html('<p style="color:red; font-weight:bold;text-align:center;">'+obj.message+'</p>');
							  }
						  });
						  return false;
						}
						*/
				</script>
			@else
				<div class="alert alert-error alert-danger">
					{{Lang::get('texts.notify_deposit_disable',array('coin'=>$wallet->name))}}
				</div>
			@endif    
		</div>
	</div>
</div>
