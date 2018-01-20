@extends('admin.layouts.master')
@section('content')	
{{ HTML::script('assets/js/bootstrap-paginator.js') }}
<h2>All Coin Giveaways</h2>
@if ( is_array(Session::get('error')) )
        <div class="alert alert-danger">{{ head(Session::get('error')) }}</div>
    @elseif ( Session::get('error') )
      <div class="alert alert-danger">{{{ Session::get('error') }}}</div>
    @endif
    @if ( Session::get('success') )
      <div class="alert alert-success">{{{ Session::get('success') }}}</div>
    @endif

    @if ( Session::get('notice') )
          <div class="alert">{{{ Session::get('notice') }}}</div>
    @endif
<div><a href="{{URL::to('admin/manage/add-coin-giveaway')}}">Add Coin Giveaway</a></div>
<table class="table table-striped" id="list-fees">
	<tr>
	 	<th>ID</th>
	 	<th>Amount</th>	
        <th>Interval</th> 
        <th>Wallet Type</th>   	
	 	<th>{{trans('admin_texts.action')}}</th>
	</tr> 	
	@foreach($giveaways as $giveaway)
		<tr>
            <td>{{$giveaway->id}}</td>
            <td>{{$giveaway->amount}}</td>
            <td>{{$giveaway->time_interval}} hrs.</td>
            <td>{{$giveaway->wallet_type}}</td>
            
            <td>
                <a href="{{URL::to('admin/edit-coin-giveaway')}}/{{$giveaway->id}}" class="edit_page">{{trans('admin_texts.edit')}}</a>  | 
                <a href="#" onclick="deletePost({{$giveaway->id}})" class="delete_page">{{trans('admin_texts.delete')}}</a>
            </td>
        </tr>
	@endforeach
</table>
<div id="pager"></div>
<div id="messageModal" class="modal hide fade" tabindex="-1" role="dialog">     
    <div class="modal-body">  
    ...      
    </div>
    <div class="modal-footer">
        <button class="btn close-popup" data-dismiss="modal">{{{ trans('texts.close')}}}</button>
    </div>
</div>

<script type='text/javascript'>

function deletePost(giveaway_id){
	
	
		$.ajax({
			type: 'post',
			url: '<?php echo action('admin\\AdminSettingController@deleteCoinGiveaway')?>',
			datatype: 'json',
			data: {isAjax: 1, giveaway_id: giveaway_id },
			beforeSend: function(request) {
				return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
			},
			success:function(response) {
				var obj = $.parseJSON(response);

				console.log('obj: ',obj);
				if(obj.status == 'success'){
					location.reload();
				}else{
					alert(obj.message);
					//$('#messageModal .modal-body').html('<p style="color:red; font-weight:bold;text-align:center;">'+obj.message+'</p>');
				}
				
				/*
				if(obj.status == 'success')
					showMessage(obj.messages,'success');                       
				else
					showMessage(obj.messages,'error');
				*/

			}, error:function(response) {
				showMessageSingle('{{{ trans('texts.error') }}}', 'error');
			}
		});
	/*
    $.post('<?php echo action('admin\\AdminSettingController@deleteCoinGiveaway')?>', {isAjax: 1, giveaway_id: giveaway_id }, function(response){
        var obj = $.parseJSON(response);
        console.log('obj: ',obj);
        if(obj.status == 'success'){
            location.reload();
        }else{
            alert(obj.message);
            //$('#messageModal .modal-body').html('<p style="color:red; font-weight:bold;text-align:center;">'+obj.message+'</p>');
        }
        //$('#messageModal').modal({show:true, keyboard:false}); 
    });
	*/
	
    return false;
	
}
    var options = {
        currentPage: <?php echo $cur_page ?>,
        totalPages: <?php echo $total_pages ?>,
        alignment:'right',
        pageUrl: function(type, page, current){
        	return "<?php echo URL::to('admin/content/all-news'); ?>"+'/'+page; 
        }
    }
    $('#pager').bootstrapPaginator(options);
</script>
@stop