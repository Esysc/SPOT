/**
 *
 * Javascript / jQuery login functions
 *
 *
 */


$(document).ready(function () {
   
    /* hide error div if jquery loads ok
     *********************************************/
    $('div.jqueryError').hide();
    $('div.loading').hide();


    /*	loading spinner functions
     *******************************/
    function showSpinner() {
        $('div.loading').show();
    }
    function hideSpinner() {
        $('div.loading').fadeOut('fast');
    }

    /*	Login redirect function if success
     ****************************************/
    function loginRedirect() {
        var base = $('.iebase').html();
        window.location = base;
    }

    /*	submit login
     *********************/
    $('form#login').submit(function () {
        //show spinner
        showSpinner();
        //stop all active animations
        $('div#loginCheck').stop(true, true);

        var logindata = $(this).serialize();

        $('div#loginCheck').hide();
        //post to check form
        $.post('app/login/login_check.php', logindata, function (data) {
            $('div#loginCheck').html(data).fadeIn('fast');
            //reload after 2 seconds if succeeded!
            if (data.search("alert alert-success") != -1) {
                showSpinner();
                //search for redirect
                if ($('form#login input#phpipamredirect').length > 0) {
                    setTimeout(function () {
                        window.location = $('form#login input#phpipamredirect').val();
                    }, 1000);
                }
                else {
                    setTimeout(loginRedirect, 1000);
                }
            }
            else {
                hideSpinner();
            }
        });
        return false;
    });

    /*	submit IP request
     *****************************************/
    $(document).on("submit", "#requestIP", function () {
        var subnet = $('#requestIPsubnet').serialize();
        var IPdata = $(this).serialize();
        var postData = subnet + "&" + IPdata;

        showSpinner();

        //post to check form
        $.post('app/login/request_ip_result.php', postData, function (data) {
            $('div#requestIPresult').html(data).slideDown('fast');
            hideSpinner();
            //reset sender to prevent duplicates on success
            if (data.search("alert alert-success") != -1) {
                $('form#requestIP input[type="text"]').val('');
                $('form#requestIP textarea').val('');
            }
        });
        return false;
    });
// clear request field
    $(".clearIPrequest").click(function () {
        $('form#requestIP input[type="text"]').val('');
        $('form#requestIP textarea').val('');

    });

    /*	submit SUNET request
     *****************************************/
    $(document).on("submit", "#requestSUBNET", function () {

        var SUBNETdata = $(this).serialize();
        var postData = SUBNETdata;

        showSpinner();

        //post to check form
       
        $.post('app/login/request_subnet_result.php', postData, function (data) {
            $('div#REQUESTsubnetresult').html(data).slideDown('fast');
            hideSpinner();
            //reset sender to prevent duplicates on success
            if (data.search("alert alert-success") != -1) {
                $('form#requestSUBNET input[type="text"]').val('');
                $('form#requestSUBNET textarea').val('');
            }
        });
        return false;
    });
// clear request field
    $(".clearSUBNETrequest").click(function () {
        $('form#requestSUBNET input[type="text"]').val('');
        $('form#requestSUBNET textarea').val('');

    });

    //Check overlap
    $(document).on('change onblur', '#subnet', function () {
        var SUBNETdata = $(this).serialize();
        var postData = SUBNETdata;

        showSpinner();
        $.post('app/login/request_subnet_overlap.php', postData, function (data) {
            $('div#REQUESTsubnetresult').html(data).slideDown('fast');
            hideSpinner();
            //reset sender to prevent duplicates on success
            if (data.search("alert alert-danger") != -1) {
                $('form#requestSUBNET input[type="text"]').val('');
                $('form#requestSUBNET textarea').val('');

            }
        });
        $('#get-subnet').tooltip('destroy');
        $('#get-subnet').attr('title', 'Suggest new subnet');
        $('#get-subnet').tooltip();
        return false;
    });
    $(document).on("click", "#get-subnet", function () {
        showSpinner();
        var action = "add";
        var sectionId = $(this).attr('data-sectionId');
        var subnet = $('form#requestSUBNET input[name=subnet]').val();
        $.post("app/admin/subnets/suggest_new.php", {action: action, sectionId: sectionId, subnet: subnet}, function (data) {
            $('form#requestSUBNET input[name=subnet]').val(data).trigger('change');
            $('#get-subnet').tooltip('destroy');
            $('#get-subnet').attr('title', 'Suggest another...');
            $('#get-subnet').tooltip();

        }).fail(function (jqxhr, textStatus, errorThrown) {
            showError(jqxhr.statusText + "<br>Status: " + textStatus + "<br>Error: " + errorThrown);
        });
        hideSpinner();
        return false;
    });
    $(document).on("change", "#help_customer", function () {
        showSpinner();

        var customer = $(this).val();
        if (customer !== '') {
            $('#owner').val(customer);

            hideSpinner();
        }
        return false;
    });
    $(document).on("change", "#help_location", function () {
        showSpinner();

        var location = $(this).val();
        if (location !== '') {
            $('#location').val(location).trigger('change');

            hideSpinner();
        }
        return false;
    });
    $(document).on("change", "#help_vlan", function () {
        showSpinner();

        var vlan = $(this).val();
        if (vlan !== '') {
            $('#vlan').val(vlan);
            $('#button_vlan').tooltip('destroy');
            $('#button_vlan').attr('title', $('#help_vlan').val());
            $('#button_vlan').tooltip();

            hideSpinner();
        }
        return false;
    });
    $('#modalLocation').on('shown.bs.modal', function () {
        $('.chosen-select', this).chosen();
    });
    $('#modalCustomer').on('shown.bs.modal', function () {
        $('.chosen-select', this).chosen();
    });
    $('#modalVlan').on('shown.bs.modal', function () {
        $('.chosen-select', this).chosen();
    });
    $('#vlan').val($('#help_vlan').val());
    $('#button_vlan').attr('title', $('#help_vlan').val());

    $(document).on('change blur', '#location', function (e) {
        e.preventDefault();
        var address = $('#location').val();
        $.get('app/admin/locations/find_geocode_from_address.php?address=' + address, function (data) {
            if (data) {
                var obj = JSON.parse(data);
                $('#verif').removeClass('text-danger');
                $('#verif').addClass('text-success').html('Coordinates verified: lat ' + obj.lat + ' and long ' + obj.long + ' !');
               
                $('#location').val(obj.long_name);


            } else {
                $('#verif').removeClass('text-success');
                $('#verif').addClass('text-danger').html('WARNING!! Coordinates not found! check the address.');
                $('#location').val('');
            }
        });

    });
});


