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
                // $('a[href*=' + switch_id + ']').trigger('click');
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
        $('#listitems li').each( function() { $(this).find('span').first().removeClass('label-success').addClass('label-default')});
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
                $('button').attr('disabled', true)
                var host = $(this).attr('data-switch');
                var postdata = "host=" + host;
                $.post('loadDefault.php', postdata, function (data) {
                    $.get(href, function (data) {
                        hideSpinner();
                        $('button').attr('disabled', false)
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
        $('#listitems li').find('span').removeClass('label-success').addClass('label-default')
        showSpinner();
        var href = $(this).attr('href');
        $.get(href, function (data) {
            $('div.col-md-9').html(data)
            hideSpinner();
            $('#listdash').paginate({itemsPerPage: 5});
        })
    });
    $('#compare').on('click', function (e) {
        e.preventDefault();
        $('#listitems li').find('span').removeClass('label-success').addClass('label-default')
        showSpinner();
        var href = $(this).attr('href');
        $.get(href, function (data) {
            $('div.col-md-9').html(data)
            hideSpinner();
            $('select').chosen();
            $('#comparative_view').on('submit', function (e) {
                e.preventDefault();
                showSpinner();
                var href = $(this).attr('action');
                var sw1 = $('#switch_id_1').val();
                var sw2 = $('#switch_id_2').val();
                var postdata = "switch_id_1=" + sw1 + "&switch_id_2=" + sw2;
                $.post(href, postdata, function (data) {
                    $('div.col-md-9').html(data)
                    hideSpinner();
                    $('.map').attr('disabled');
                    $('.map').on('click', function (e) {
                        e.preventDefault();
                    });
                });
            });
        })
    });
    $('#logout').on('click', function () {
        $.post('login_form.php', 'logout=true', function (data) {
            window.location.href = 'login_form.php?logout=true';
        });
    });
    function setIntervalAndExecute(fn, t) {
        fn();
        return(setInterval(fn, t));
    }

    $(document).on('click', '.diff', function (e) {
        e.preventDefault();

        /*
         * Insert remote command to DB and parse the results
         */
        var scriptID = 33; /* the script ID for rancidDiff */
        var rancid_server = "x.x.x.204"
        var url = "/SPOT/provisioning/api/remotecommands/";

        var switch_ip = $(this).attr('ipattr');
        var classToAttr = $(this).attr('id');
        var ele = $(this);
        ele.replaceWith('<span class="buttonoverlay ' + classToAttr + '"><img src="web/images/loader.gif" title="Loading... please wait"/></span>');

        var options = {0: " -H " + switch_ip};
        options = JSON.stringify(options);
        var command = {
            salesorder: 99999999,
            rack: 25,
            shelf: "Z",
            clientaddress: rancid_server,
            arguments: options,
            exesequence: 0,
            returnstdout: "Waiting for command execution",
            executionflag: 1,
            scriptid: scriptID

        }
        var Jcommand = JSON.stringify(command);
        //Post the remote command to get executed
        $.ajax({
            url: url,
            type: "POST",
            data: Jcommand,
            success: function (data) {
                var mymon = setInterval(function () {
                    var commandId = data.remotecommandid;
                    var url = "/SPOT/provisioning/api/remotecommands/" + commandId

                    // Need to be a separate ajax call
                    var temp = "/SPOT/provisioning/api/tempdata/" + commandId;
                    $.ajax({
                        url: temp,
                        type: "GET",
                        success: function (a) {

                            if (typeof a === 'object' && typeof a.message !== 'undefined') {
                                if (a.status === 'RUNNING') {
                                    if (a.message !== '') {
                                        
                                        $('span.' + classToAttr).html(a.message);
                                        $('span.' + classToAttr).find('img').attr('title', "Loading.... Please wait")
                                    }
                                }
                                if (a.status === 'END') {
                                    if (a.message !== '') {
                                        $('span.' + classToAttr).removeClass('buttonoverlay');
                                        $('span.' + classToAttr).html(a.message);
                                    } else { 
                                        $('span.' + classToAttr).addClass('label label-success').html('Config OK');
                                        setTimeout(function() {
                                            $('span.' + classToAttr).replaceWith(ele);
                                        }, 5000)
                                    }
                                    
                                    clearInterval(mymon);
                                }
                            }
                        }
                    });
                }, 1000);
            },
            error: function (data) {
                console.log("Error");
                $(this).html("An error occured:  " + data.statusText + " " + data.responseText);
            }
        })
    });
});