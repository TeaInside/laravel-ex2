<div class="row">
	<div class="col-12-xs col-sm-12 col-lg-12">

			
		<!-- Giveaway -->
		<div id="form_giveaway">
			<div style="color:red">@if(isset($error_message)) {{$error_message}} @endif</div>
			<h2>Coin Giveaway</h2> 

		Here you can get FREE coins every 24hrs! (only valid to members with a trade history)
		<BR><BR>
		These FREE coins have either been donated or purchased by {{{ Config::get('config_custom.company_name') }}}. <BR><BR>
		Cheating the system by using mulitiple accounts, to get more than your fair share of Free coins, will result in a lifetime ban,  and all your coins donated to the Giveaway pool.
		<BR><BR>
			@if ( Session::get('error') )
				<div class="alert alert-error alert-danger">
					<button type="button" class="close" data-dismiss="alert">×</button>
					@if ( is_array(Session::get('error')) )
						{{ head(Session::get('error')) }}
					@else
						{{ Session::get('error') }}
					@endif
				</div>
			@endif

			@if ( Session::get('notice') )
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert">×</button>
					{{ Session::get('notice') }}
				</div>
			@endif  

			<table class="table table-striped">
				<tbody>
					<tr>
						<th width="32">Logo</th>
						<th>Status</th>
						<th>Symbol</th>
						<th>Free Amount</th>
						<th>Giveaway Pool</th>
					</tr>
					@foreach($giveaways as $giveaway)
					<tr>
						<td align="center">
							@if(!empty($giveaway['logo']))                        
								<img width="23" border=0 height="23" src="{{asset('')}}/{{$giveaway['logo']}}" />
							@else
							&nbsp;
							@endif
						</td>
						<td>
							<a href="#" class="claim-btn" data-id="{{$giveaway['id']}}" class="btn btn-info"><b>Get Free Coins</b></a>
							
							@if ($giveaway['coins_left'] < $giveaway['amount'])
								<font color=red>Pool empty</font>
							@else
								@if ($giveaway['claim'] == true)
								<a href="#" class="claim-btn" data-id="{{$giveaway['id']}}" class="btn btn-info"><b>Get Free Coins</b></a>
								@else
								Come back tomorrow
								@endif
							@endif
						</td>
						<td>{{$giveaway['wallet_type']}}</td>
						<td>{{ round($giveaway['amount'],0) }}</td>
						<td>{{$giveaway['coins_left']}}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		<div id="messageModal" class="modal">
			<div class="modal-dialog">
				<div class="modal-content">      
				  <div class="modal-body">
				  </div>
				  <div class="modal-footer"> 
					<button type="button" class="btn btn-default" data-dismiss="modal">{{{ trans('user_texts.close')}}}</button>
				  </div>
				</div>
			  </div>
		</div>
		<script>
		jQuery(function($) {
			$('a.claim-btn').on('click', function(e) {
				e.preventDefault();
				$(this).attr('disabled','disabled');
				$.post('<?php echo action('UserController@doCoinGiveaway')?>', {
						isAjax: 1, 
						giveaway_id: $(this).data('id') 
					}, function(response){
						var obj = $.parseJSON(response); 
						if(obj.status == 'success'){               
							$('#messageModal .modal-body').html('<p style="color:#008B5D; font-weight:bold;text-align:center;">'+obj.message+'</p>');            
							$('#messageModal').on('hidden.bs.modal', function (e) {              
								//location.reload();
							});
						} else {
							$('#messageModal .modal-body').html('<p style="color:red; font-weight:bold;text-align:center;">'+obj.message+'</p>');
						}              
						$('#messageModal').modal({show:true});  
				});
			});
		});
		</script>
	</div>
</div>