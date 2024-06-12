$(document).ready(function() {
    $('.edit_data4, .edit_data5').on('click', function() {
        var editId = $(this).attr('id');
        var targetModal = $(this).hasClass('edit_data4') ? '#editData4' : '#editData5';
        var targetUrl = $(this).hasClass('edit_data4') ? 'edit_category.php' : 'view_category.php';

        $.ajax({
            url: targetUrl,
            type: "post",
            data: { edit_id: editId },
            success: function(data) {
                $(targetModal).find('.modal-body').html(data);
                var modal = new bootstrap.Modal($(targetModal));
                modal.show();
            }
        });
    });

    $(document).on('click', '.edit_data4', function(){
        var edit_id4 = $(this).attr('id');
        $.ajax({
            url: "edit_category.php",
            type: "post",
            data: { edit_id4: edit_id4 },
            success: function(data){
                $("#info_update4").html(data);
                $("#editData4").modal('show');
            }
        });
    });

    $(document).on('click', '.edit_data5', function(){
        var edit_id5 = $(this).attr('id');
        $.ajax({
            url: "view_category.php",
            type: "post",
            data: { edit_id5: edit_id5 },
            success: function(data){
                $("#info_update5").html(data);
                $("#editData5").modal('show');
            }
        });
    });
});
