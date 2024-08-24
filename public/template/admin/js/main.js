$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function removeRow(id, url)
{
    if (confirm('Bạn có chắc chắn muốn xóa dữ liệu này không?')) {
        $.ajax({
            url: url,
            datatype: 'JSON',
            type: 'DELETE',
            data: {id},
            success: function (result) {
                if(result.error === false) {
                    alert(result.message);
                    location.reload();
                }
                else {
                    alert(result.message);
                }
            }
        });
    }
}

/*Upload file*/
$('#upload').change(function () {
    const form = new FormData();
    form.append('file', $(this)[0].files[0]);
    $.ajax({
        url: '/admin/upload/services',
        type: 'POST',
        datatype: 'JSON',
        data: form,
        contentType: false,
        processData: false,
        success: function (result) {
            if (result.error === false) {
                $('#image_show').html('<a href="' + result.url + '" target="_blank"><img src="' + result.url + '" width="100px"></a>');
                $('#thumb').val(result.url);
            } else{
                alert('Upload file thất bại');
            }
        }

    });
});
