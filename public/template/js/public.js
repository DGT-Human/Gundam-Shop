$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function loadMore(){
    const page = $('#page').val();
    $.ajax({
        type: "POST",
        dataType: "json",
        data: {page},
        url: "services/load-products",
        success:function (result){
            if(result.html !== '') {
                $('#loadProduct').append(result.html);
                $('#page').val(parseInt(page) + 1);
            } else {
                $('#loadMore').hide();
            }
        }
    })
}
