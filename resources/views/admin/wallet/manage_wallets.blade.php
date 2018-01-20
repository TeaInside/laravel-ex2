@extends('admin.layouts.master')
@section('content')	
{{ HTML::script('assets/js/bootstrap-paginator.js') }}
<h2>{{trans('admin_texts.wallets')}}</h2>

@if ( is_array(Session::get('error')) )
        <div class="alert alert-error">{{ head(Session::get('error')) }}</div>
	@elseif ( Session::get('error') )
      <div class="alert alert-error">{{{ Session::get('error') }}}</div>
	@endif
	@if ( Session::get('success') )
      <div class="alert alert-success">{{{ Session::get('success') }}}</div>
	@endif

	@if ( Session::get('notice') )
	      <div class="alert">{{{ Session::get('notice') }}}</div>
	@endif
<a href="#" id="add_wallet_link">{{trans('admin_texts.add_wallet')}}</a>

<form class="form-horizontal" role="form" id="add_new_wallet" method="POST" action="{{{ Auth::check('admin\\AdminSettingController@addNewWallet') ?: URL::to('/admin/add-wallet') }}}" enctype="multipart/form-data">
	<input type="hidden" name="_token" value="{{{ Session::token() }}}">
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label">{{trans('admin_texts.coin_code')}}</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <input type="text" class="form-control" required="" name="type" id="type" value="{{{ Input::old('type') }}}">
			</div>	      	      
	    </div>
	</div>	
	<div class="form-group">
	    <label for="inputPassword3" class="col-sm-2 control-label">{{trans('admin_texts.coin_name')}}</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <input type="text" name="name" id="name" required="" class="form-control" value="{{{ Input::old('name') }}}">
			</div>	      
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputPassword3" class="col-sm-2 control-label">{{trans('admin_texts.wallet_username')}}</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <input minlength="2" type="text" required="" class="form-control" name="wallet_username" id="wallet_username" value="{{{ Input::old('wallet_username') }}}">			  
			</div>
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputPassword3" class="col-sm-2 control-label">{{{ Lang::get('confide::confide.password') }}}</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			 <input type="text" name="password" id="password" class="form-control" required="">		  
			</div>
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputPassword3" class="col-sm-2 control-label">{{trans('admin_texts.ip')}}</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <input class="form-control" type="text" name="ip" id="ip" required="">		  
			</div>
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputPassword3" class="col-sm-2 control-label">{{trans('admin_texts.port')}}</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <input class="form-control" type="text" name="port" id="port" required="">		  
			</div>
	    </div>
	</div>
	<div class="form-group">
	    <label for="confirm_count" class="col-sm-2 control-label">Confirm Count</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <input class="form-control" type="text" name="confirm_count" id="confirm_count" required="" value="">		  
			</div>
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputPassword3" class="col-sm-2 control-label">{{trans('admin_texts.download_wallet_client')}}</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <input class="form-control" type="text" name="download_wallet_client" id="download_wallet_client">		  
			</div>
	    </div>
	</div>
	<div class="form-group">
	    <label class="col-sm-2 control-label">{{trans('admin_texts.logo_coin')}}</label>
	    <div class="col-sm-10">
	      <input type="file" class="form-control" id="logo_coin" name="logo_coin">	     
	    </div>
	</div>
	<div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10">
	      <div class="checkbox">
	        <label>	         
	          <input type="checkbox" id="enable_deposit" name="enable_deposit" value="1" checked> {{trans('admin_texts.enable_deposit')}}
	        </label>
	      </div>
	    </div>
  	</div>	
  	<div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10">
	      <div class="checkbox">
	        <label>	         
	          <input type="checkbox" id="enable_withdraw" name="enable_withdraw" value="1" checked> {{trans('admin_texts.enable_withdraw')}}
	        </label>
	      </div>
	    </div>
  	</div>  	
  	<div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10">
	      <div class="checkbox">
	        <label>	         
	          <input type="checkbox" id="enable_trading" name="enable_trading" value="1"  checked> {{trans('admin_texts.enable_trading')}}
	        </label>
	      </div>
	    </div>
  	</div>  	
	
	<div class="form-group">		
	    <div class="col-sm-offset-2 col-sm-10">
	      <button type="submit" class="btn btn-primary" id="do_add">Add</button>
	    </div>
	</div>
</form>
<table class="table table-striped" id="list-fees">
	<tr>	 	
	 	<th>{{trans('admin_texts.coin_code')}}</th>
	 	<th>{{trans('admin_texts.coin_name')}}</th>	 	
	 	<th>{{trans('admin_texts.wallet_username')}}</th>
	 	<th>{{trans('admin_texts.ip')}}</th>	 	
	 	<th>{{trans('admin_texts.port')}}</th>
	 	<th>{{trans('admin_texts.action')}}</th>
	</tr> 	
	@foreach($wallets as $wallet)
	<tr id="wallet_id_{{$wallet->id}}">
		<td>
			{{$wallet->type}}
			@if ( $wallet->enable_trading == 0)
				<span style="color: red;">{{ trans('texts.disabled') }}</span>
				<i title="{{$wallet->type}} - {{ trans('texts.market_disabled') }}" data-placement="bottom" data-toggle="tooltip" class="icon-exclamation-sign icon-large"></i>
			@endif
			
		</td>
		<td>{{$wallet->name}}</td>
		<td>{{$wallet->wallet_username}}</td>
		<td>{{$wallet->wallet_ip}}</td>
		<td>{{$wallet->port}}</td>
		<td><a href="{{URL::to('admin/edit-wallet')}}/{{$wallet->id}}" class="edit_wallet">{{trans('admin_texts.edit')}}</a>  | <a href="#" onclick="deleteWallet({{$wallet->id}})" class="delete_wallet">{{trans('admin_texts.delete')}}</a></td>
	</tr>
	@endforeach	
</table>
<div id="pager"></div>

{{ HTML::script('assets/js/jquery.validate.min.js') }}
<script type="text/javascript">

		
function deleteWallet(wallet_id){
	$.post('<?php echo action('admin\\AdminSettingController@deleteWallet')?>', {isAjax: 1, wallet_id: wallet_id }, function(response){
       	var obj = $.parseJSON(response);
		var title ='Wallet removal';
		var msg ='';
		
	    console.log('obj: ',obj);
	    if(obj.status == 'success'){			
			
			msg = '<p style="color:#008B5D; font-weight:bold;text-align:center;">'+obj.message+'</p>';
			$('#wallet_id_'+wallet_id).fadeOut();
			
            //$('#messageModal').on('hidden.bs.modal', function (e) {
              //location.reload();
            //});
        }else{
			msg = '<p style="color:red; font-weight:bold;text-align:center;">'+obj.message+'</p>';
        }
		BootstrapDialog.show({
			title: title,
			message: msg
		});
		
		

    });
    return false;
}

    $(document).ready(function() {
    	$('#add_new_wallet').hide();
        $('#add_wallet_link').click(function(event) {
        	$('#add_new_wallet').toggle("slow");
        });
        $("#add_new_wallet").validate({
            rules: {
                type: "required",
                name: "required",
                ip: "required",
                port: {
                    required: true,
                    number: true
                },                
                password: "required",
            },
            messages: {
                type: "Please provide a type of wallet.", 
                name: "Please provide a name of wallet.", 
                ip: "Please provide a ip.",  
                port: {
                    required: "Please provide a port.",
                    number: "Port must be a number."
                },              
                password: "Please provide a password.",
            }
	});

   });
</script>
<script type='text/javascript'>
    var options = {
        currentPage: <?php echo $cur_page ?>,
        totalPages: <?php echo $total_pages ?>,
        alignment:'right',
        pageUrl: function(type, page, current){
        	return "<?php echo URL::to('admin/manage/wallets'); ?>"+'/'+page; 
        }
    }
    $('#pager').bootstrapPaginator(options);
</script>
@stop