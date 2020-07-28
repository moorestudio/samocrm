    jQuery(document).on('click', '#save_active_client', function (e) {
        var id = $(e.currentTarget).data('id');
        var fullname = document.getElementById('0_list').checked;
        var email = document.getElementById('1_list').checked;
        var contacts = document.getElementById('2_list').checked;
        var city = document.getElementById('3_list').checked;
        var country = document.getElementById('4_list').checked;
        var promo_name = document.getElementById('5_list').checked;
        var promo_discount = document.getElementById('6_list').checked;
        var q_bought = document.getElementById('7_list').checked;
        $.ajax({
            url: '/save_active_client',
            method: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                "id": id,
                "fullname": fullname,
                "email": email,
                "contacts": contacts,
                "city": city,
                "country": country,
                "promo_name": promo_name,
                "promo_discount": promo_discount,
                "q_bought": q_bought,
            },
            success: data => {
                $('.preloader').fadeIn('slow');
                $('#ActiveClient').modal('hide');
                location.reload();
                // $('.report_block').removeClass('disappear');
            },
            error: () => {
                // $('.report_block').removeClass('disappear');
            }
        })
    });

    jQuery(document).on('click', '#save_franch_option', function (e) {
        var id = $(e.currentTarget).data('id');
        var fullname = document.getElementById('0_list').checked;
        var email = document.getElementById('1_list').checked;
        var contacts = document.getElementById('2_list').checked;
        var city = document.getElementById('3_list').checked;
        var country = document.getElementById('4_list').checked;

        // var company = document.getElementById('5_list').checked;
        // var job = document.getElementById('6_list').checked;

        var inn = document.getElementById('5_list').checked;
        var contract_start = document.getElementById('6_list').checked;
        var contract_end = document.getElementById('7_list').checked;



        $('.preloader').fadeIn('slow');
        $.ajax({
            url: '/save_franch_option',
            method: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                "id": id,
                "fullname": fullname,
                "email": email,
                "contacts": contacts,
                "city": city,
                "country": country,
                // "company": company,
                // "job": job,
                "inn": inn,
                "contract_start": contract_start,
                "contract_end": contract_end,
            },
            success: data => {
                $('#ActiveClient').modal('hide');
                location.reload();
                // $('.report_block').removeClass('disappear');
            },
            error: () => {
                // $('.report_block').removeClass('disappear');
            }
        })
    });

    jQuery(document).on('click', '.option_list', function (e) {
        var btn = $(e.currentTarget);

        if (btn.prop('checked') == true && $('.check-list').length < 4)
        {
            btn.addClass('check-list');
        }
        else if (btn.prop('checked') == false)
        {
            btn.removeClass('check-list');
        }
        else
        {
            btn.prop("checked", false);
        }
    });
