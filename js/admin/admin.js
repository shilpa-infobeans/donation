const _ = jQuery;
_(document).ready(function(){

    new DataTable('#donation_data', {
        ajax: global_object.ajaxurl,
        processing: true,
        serverSide: true,
        serverMethod: 'get',
        searching : true,
        order: [[ 1, "desc" ]],
        columns: [
            { data: 's_n' },
            { data: 'donator_name' },
            { data: 'donator_phone' },
            { data: 'donator_email' },
            { data: 'donation_amount' },
            { data: 'payment_status' },
         ]
    });
 });

