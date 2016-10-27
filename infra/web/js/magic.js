/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    /*loading spinner functions */
    function showSpinner() {
        $('div.loading').show();
    }
    function hideSpinner() {
        $('div.loading').fadeOut('fast');
    }
    function vlan_page() {

    }
//Edit a single port popup form
    $(document).on('click', 'div.vlan-list-ports a', function (e) {

        e.preventDefault();
        var url = $(this).attr('href');
        showSpinner();
        $.ajax({
            url: url,
            success: function (data) {

                $('#list_vlans').html(data);
                $('#list_vlans_modal').modal('show');
                var port_id = $('#port_id').val();

                if (port_id > 24) {
                    $('#getMacTable').hide();
                } else {
                    $('#getMacTable').show();
                }

            },
            complete: function () {
                $('select').chosen({width: "100%"}).trigger('chosen:updated');
                hideSpinner()
            }

        });

    });
    $(document).on('click', 'div.switch_table_container_small a', function (e) {

        e.preventDefault();
        var url = $(this).attr('href');
        showSpinner();
        $.ajax({
            url: url,
            success: function (data) {

                $('#list_vlans').html(data);
                $('#list_vlans_modal').modal('show');
                var port_id = $('#port_id').val();
                if (port_id > 24) {
                    $('#getMacTable').hide();
                } else {
                    $('#getMacTable').show();
                }

            },
            complete: function () {
                $('select').chosen({width: "100%"}).trigger('chosen:updated');
                hideSpinner()
            }

        });

    });
    /*
     * Prevent edit port form submit
     */
    $(document).on('submit', "#edit_port", function (e) {
        showSpinner()
        e.preventDefault();
        var url = "untag_port.php";
        var switch_id = $('#switch_id').val();
        var source_vlan = $('#source_vlan').val();
        var port_id = $('#port_id').val();
        var dest_vlan = $('#dest_vlan').val();
        var postdata = "switch_id=" + switch_id + "&source_vlan=" + source_vlan + "&port_id=" + port_id + "&dest_vlan=" + dest_vlan;
        $.post(url, postdata, function (data) {
            $('#message').html(data);

            showSpinner();
            var href = "list_vlans.php";
            $('#list_vlans_modal').modal('hide');
            $.get(href, postdata, function (data) {
                hideSpinner();

                $('div.col-md-9').html(data)
                $('#default').on('click', function (e) {
                    e.preventDefault();
                    showSpinner();
                    $('.switch_table_container').fadeTo(1000, 0.4);
                    var host = $(this).attr('data-switch');
                    var post = "host=" + host;
                    $.post('loadDefault.php', post, function (data) {
                        $.get(href, postdata, function (data) {
                            hideSpinner();
                            $('div.col-md-9').html(data)
                        });
                    });
                });
            });
        })

    });
    $(document).on('click', "#getMacTable", function (e) {
        showSpinner();
        var switch_id = $('#switch_id').val();
        var port_id = $('#port_id').val();
        var postdata = "switch_id=" + switch_id + "&port_id=" + port_id;
        $.post('get_mac_table.php', postdata, function (data) {
            $('#result').html(data)
            hideSpinner();
        });
    });
    $(document).on('hidden.bs.modal', function () {

        // showSpinner();
        // location.reload();
    })
    // Show the spiner loading pages
    function showProgress() {
        showSpinner();
        $(window).load(function () {
            hideSpinner();
        });
    }
    $('a').on('click', function () {
        var href = $(this).attr('href') ? $(this).attr('href') : '';
        if (href !== '' && href !== "#")
            showProgress();

    });
    showProgress();
    $('img').addClass('img-rounded img-responsive')

    $('#listitems').paginate({itemsPerPage: 10});



    $('#listitems li').children("a").on('click', function (e) {
        $('#listitems li').find('span').removeClass('label-success').addClass('label-default')
        var span = $(this).children().children("span");
        span.removeClass('label-default').addClass('label-success')

        e.preventDefault()
        var href = $(this).attr('href');
        $.get(href, function (data) {
            $('div.col-md-9').html(data)
            $('#default').on('click', function (e) {
                e.preventDefault();
                showSpinner();
                $('.switch_table_container').fadeTo(1000, 0.4);
                var host = $(this).attr('data-switch');
                var postdata = "host=" + host;
                $.post('loadDefault.php', postdata, function (data) {
                    $.get(href, function (data) {
                        hideSpinner();
                        $('div.col-md-9').html(data)
                    });
                });
            });
            $('#displayconf').on('click', function (e) {
                e.preventDefault();

                showSpinner();
                var href = $(this).attr('href');
                $('#delete_selected_vlans').remove();

                $.get(href, function (data) {
                    $('#toolTipsSwitchDetails').after(data)
                    hideSpinner();
                })
            })

            hideSpinner();
        })
    });
    $('#dashboard').on('click', function (e) {
        e.preventDefault();
        showSpinner();
        var href = $(this).attr('href');
        $.get(href, function (data) {
            $('div.col-md-9').html(data)
            hideSpinner();
            $('#listdash').paginate({itemsPerPage: 5});
        })
    });
    $('#logout').on('click', function () {
        $.post('login_form.php', 'logout=true', function (data) {
            window.location.href = 'login_form.php?logout=true';
        });

    });

})