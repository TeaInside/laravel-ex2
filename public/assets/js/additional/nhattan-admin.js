$(document).ready(function(){
    $('#addPoolForm').validate({
        rules: {
            coin: "required",
			blockviewer: {
				required: true,
				url: true
			},
			forum: {
				required: true,
				url: true
			},
            url: {
				required: true,
				url: true
			}
        }
    });
    $('.edit-pool').click(function(){
        var id = $(this).attr('data-id');
        var modal = $('#editPoolModal');
        $.post('pool/get-edit-pool', {id: id}, function(response){
            modal.find('.modal-body').html(tmpl("tmpl-edit", response));
            $('#modalEditForm').validate({
                rules: {
                    coin: "required",
					blockviewer: {
						required: true,
						url: true
					},
					forum: {
						required: true,
						url: true
					},
					url: {
						required: true,
						url: true
					}
                }
            });
            modal.modal('show');
        }, 'json');
    });
    $('.remove-pool').click(function(){
        var id = $(this).attr('data-id');
        $.post('pool/remove-pool', {id: id}, function(response){
            location.reload();
        });
    })
});
