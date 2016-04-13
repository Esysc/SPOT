$(document).ready(function () {



    $('.imagename').on('change', function () {


        $(this).next('.imglabel').remove();
        var id = $(this).attr('id');
        var imglblcontainer = $('#imglblcontainer' + id);
        var imageName = $(this).find(":selected").text();

        imglblcontainer.html('<span class="imglabel label label-success">Selected: ' + imageName + '</span>');


    });


    $.ajaxSettings.data = null; // hack for google chrome
    
    

    function disableF5(e) {
        if ((e.which || e.keyCode) == 116) {
            e.preventDefault();
            var url = window.location.href;
            $('#servermsg').html('Please, to refresh click on the menu the corresponding <a href="' + url + '">link</a>, refresh within F5 is disabled.');
            $('#basicModal').modal();
            return false;
        }
    }

    $('a.pmon').on('click', function (e) {
        e.preventDefault();
        window.open($(this).attr("href"), "Production Scheduling", "menubar=no,toolbar=no,width=2048,height=2048,scrollbars=yes");
       
		
    });

// To disable f5

    $(document).on("keydown", disableF5);

    /**
     *
     * @type event
     * Permits only valid keystrokes
     * numbers and dot
     */

    $('#monitoring, #ok, #pendings').on('click', function (e) {
        e.preventDefault();

    });
    function IPAddressKeyOnly(e) {
        var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
        if (keyCode != 46 && keyCode > 31 && (keyCode < 48 || keyCode > 57))
            return false;
        return true;
    }
    var popcontent = '';
    var BGscriptname = '';
    
    var bgcommands = function () {

        var d = new Date();
        popcontent = '<p class="alert alert-info"><strong>Last checked: ' + d.toLocaleTimeString() + '</strong></p>';
        var count = 0;
        $('#ok').remove();
        $('#monitoring').remove();


        $.ajax({
            url: '/SPOT/provisioning/api/remotecommandses?executionflag_In=9,0&scriptid_NotEquals=14', //The script id mondwrapper_2.0 not to show here
            async: false,
            success: function (results) {

                if (results) {
                    var objects = results.rows;
                    // console.log(objects); 

                    if (objects.length > 0) {

                        popcontent += '<table class="table table-striped table-bordered table-condensed table-responsive table-hover"><tr><th><strong><center>Scripts</center></strong></th></tr>';

                        $.each(objects, function (key, val) {
                            count++;
                            popcontent += '<tr><td><strong>Exe n. ' + count + '</strong></tr></td>';
                            popcontent += '<tr><td><strong>CommandID: </strong>' + val.remotecommandid + '</td></tr>';
                            popcontent += '<tr><td><strong>Server:  </strong> ' + val.clientaddress + '</td></tr>';

                            $.ajax({
                                url: '/SPOT/provisioning/api/provisioningscriptses?scriptid_Equals=' + val.scriptid,
                                async: false,
                                success: function (data) {
                                    var objs = data.rows[0];

                                    BGscriptname = objs.scriptname;

                                }

                            });

                            popcontent += '<tr><td><strong>Script:  </strong>' + BGscriptname + '</td></tr>';
                            popcontent += '<tr><td><strong>Timestamp:  </strong>' + val.logtime + '</td></tr>';

                        });
                        $('#ok').remove();
                        $('#monitoring').remove();
                        $('#pendings').after('<a  id="monitoring"></a>');


                        popcontent += '</table>';
                        // $('#monitoring').html('<img src="/SPOT/provisioning/images/on.gif"/>');
                        $('#monitoring').html('Background operations <span class="badge badge-warning">' + count + '</span>');

                        // $this.webuiPopover().hide();
                        $('#monitoring').webuiPopover({
                            title: 'Processes still executing..',
                            content: popcontent,
                            placement: 'bottom-left',
                            trigger: 'hover',
                            animation: 'pop',
                            type: 'html',
                            cache: true
                        });




                    }

                }
            }
        });
        $.ajax({
            url: '/SPOT/provisioning/api/provisioningnotificationses?progress_LessThan=100',
            async: false,
            success: function (results) {
                if (results) {
                    var objects = results.rows;
                    // console.log(objects); 

                    if (objects.length > 0) {

                        popcontent += '</p><table class="table table-striped table-bordered table-condensed table-responsive table-hover"><tr><th><strong><center>Provisioning</center></strong></th></tr>';
                        var index = 0;
                        $.each(objects, function (key, val) {
                            index++;
                            count++;
                            popcontent += '<tr><td><strong>Provisioning n. ' + index + '</strong></tr></td>';
                            popcontent += '<tr><td><strong>Notifid: </strong>' + val.notifid + '</td></tr>';
                            popcontent += '<tr><td><strong>Hostname:  </strong> ' + val.hostname + '</td></tr>';
                            popcontent += '<tr><td><strong>Progress:  </strong><div class="progress progress-striped active"><div class="bar" role="progressbar" aria-valuenow="' + val.progress + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + val.progress + '%"> <span class="sr-only" >' + val.progress + '% Complete</span></div></div>' + val.status + '</td></tr>';
                            popcontent += '<tr><td><strong>Timestamp:  </strong>' + val.update + '</td></tr>';

                        });




                        popcontent += '</table>';
                        // $('#monitoring').html('<img src="/SPOT/provisioning/images/on.gif"/>');
                        $('#ok').remove();
                        $('#monitoring').remove();
                        $('#pendings').after('<a  id="monitoring"></a>');
                        $('#monitoring').html('Background operations <span class="badge badge-warning">' + count + '</span>');
                        // $this.webuiPopover().hide();
                        $('#monitoring').webuiPopover({
                            title: 'Processes still executing..',
                            content: popcontent,
                            placement: 'bottom-left',
                            trigger: 'hover',
                            animation: 'pop',
                            type: 'html',
                            cache: true
                        });


                    }

                }
            }

        });
        if (popcontent === '<p class="alert alert-info"><strong>Last checked: ' + d.toLocaleTimeString() + '</strong></p>') {
            $('#monitoring').remove();
            $('#ok').remove();
            $('#pendings').after('<a  id="ok"></a>');
            $('#ok').html('Background operations <span class="badge badge-info">' + count + '</span>');
            $('#ok').webuiPopover({
                title: 'No pending operations..',
                content: popcontent,
                placement: 'bottom-left',
                trigger: 'hover',
                animation: 'pop',
                type: 'html',
                cache: true
            });



        }
        $('.bgop').on('click', function () {
            //    $('#myModalLabel').text('Background Operations Details: (' + count + ' current)')
            var cssclass = '';
            if (count == 0) {
                cssclass = 'class="label label-info"';
            } else
            {
                cssclass = 'class="label label-warning"';
            }
            $('#servermsg').html('<h5>Background Operations Details:</h5> <center><p ' + cssclass + '>  ' + count + ' current</p></center>' + popcontent);
            $('#basicModal').modal();
        });

    };
    bgcommands();
    setInterval(bgcommands, 10000);






    $(document.body).unbind('change', '.hostname').on('change', '.hostname', function (ev) {
        //$('.hostname').unbind('change').on('change', function (ev) {
        $(this).nextAll('b.checkhostname').remove();
        var ValidHostnameRegex = new RegExp("^(([a-zA-Z]|[a-zA-Z][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z]|[A-Za-z][A-Za-z0-9\-]*[A-Za-z0-9])$");

        if (!$(this).val().match(ValidHostnameRegex) || $(this).val().length <= 4)
        {

            // fieldname = $(this).prop('name');

            //lastchar = readable[key];
            //     lastchar = $(this).val()[$(this).val().lenght -1];


            var text = '<b class="badge badge-warning checkhostname">Hostname ' + $(this).val() + ' not valid</b>';
            if ($(this).val().length <= 4)
                text = '<b class="badge badge-warning checkhostname">Hostname should be at least 5 chars long. ' + $(this).val() + ' is ' + $(this).val().length + '.</b>';

            $(this).after(text);
            $(this).val('');

            $(this).css('border', 'pink 2px solid');
            return false;


        } else {


            //$(this).css('background', '#82FA58');
            $(this).css("border", '#82FA58 2px solid');
        }


    });
    $(document.body).on('change', '.ipaddress', function (e) {

        var ip = $(this).val();
        var error = "Only valid IP address, for example 192.168.1.2. Be careful: subnet base and broadcast addressess are not allowed!";
        if (ip.length > 0 && ip.length <= 15) {



            $(this).attr('title', 'The IP address is correct');

            //$(this).css('background', '#82FA58');
            $(this).css("border", '#82FA58 2px solid');
            var ipSlot = ip.split(".");
            if (ipSlot.length == 4) {
                for (var i = 0; i < ipSlot.length; i++) {
                    var l = ipSlot[i].length;
                    if (l > 0 && l <= 3) {
                        if (ipSlot[i] >= 0 && ipSlot[i] < 256) {
                            if (i == 3 && (ipSlot[i] == 0 || ipSlot[i] == 255)) {
                                $(this).val('');
                                $(this).attr('title', error);
                                $(this).css('border', 'pink 2px solid');
                                return false;
                            }


                        } else {
                            $(this).val('');
                            $(this).attr('title', error);
                            // $(this).css('background', 'pink');
                            $(this).css('border', 'pink 2px solid');
                            return false;
                        }
                    } else {
                        $(this).val('');
                        $(this).attr('title', error);
                        // $(this).css('background', 'pink');
                        $(this).css('border', 'pink 2px solid');
                        return false;
                    }

                }
            } else {
                $(this).val('');
                $(this).attr('title', error);
                // $(this).css('background', 'pink');
                $(this).css('border', 'pink 2px solid');
                return false;
            }

        } else {
            $(this).val('');
            $(this).attr('title', error);
            //  $(this).css('background', 'pink');
            $(this).css('border', 'pink 2px solid');
            return false;
        }




    });
    $(document.body).on('change', '.subnet', function (e) {

        var ip = $(this).val();
        var error = "This subnet is not valid, type for example 192.168.1.0.";
        if (ip.length > 0 && ip.length <= 15) {



            $(this).attr('title', 'The Subnet is correct');

            //$(this).css('background', '#82FA58');
            $(this).css("border", '#82FA58 2px solid');
            var ipSlot = ip.split(".");
            if (ipSlot.length == 4) {
                for (var i = 0; i < ipSlot.length; i++) {
                    var l = ipSlot[i].length;
                    if (l > 0 && l <= 3) {
                        if (ipSlot[i] >= 0 && ipSlot[i] < 256) {
                            if (i == 3 && (ipSlot[i] != 0)) {
                                $(this).val('');
                                $(this).attr('title', error);
                                $(this).css('border', 'pink 2px solid');
                                return false;
                            }


                        } else {
                            $(this).val('');
                            $(this).attr('title', error);
                            // $(this).css('background', 'pink');
                            $(this).css('border', 'pink 2px solid');
                            return false;
                        }
                    } else {
                        $(this).val('');
                        $(this).attr('title', error);
                        // $(this).css('background', 'pink');
                        $(this).css('border', 'pink 2px solid');
                        return false;
                    }

                }
            } else {
                $(this).val('');
                $(this).attr('title', error);
                // $(this).css('background', 'pink');
                $(this).css('border', 'pink 2px solid');
                return false;
            }

        } else {
            $(this).val('');
            $(this).attr('title', error);
            //  $(this).css('background', 'pink');
            $(this).css('border', 'pink 2px solid');
            return false;
        }




    });

    /**
     *
     * @param {type} elem
     * function called onchange input elem
     * Validation of netmask
     * @returns false ,set the background color and reset value
     * @returns {Boolean}
     */


    $(document.body).on('change', '.netmask', function (e) {

        var mask = $(this).val();
        var error = "You didn't enter a valid subnet mask.";
        //m[0] can be 128, 192, 224, 240, 248, 252, 254, 255
        //m[1] can be 128, 192, 224, 240, 248, 252, 254, 255 if m[0] is 255, else m[1] must be 0
        //m[2] can be 128, 192, 224, 240, 248, 252, 254, 255 if m[1] is 255, else m[2] must be 0
        //m[3] can be 128, 192, 224, 240, 248, 252, 254, 255 if m[2] is 255, else m[3] must be 0

        var correct_range = {128: 1, 192: 1, 224: 1, 240: 1, 248: 1, 252: 1, 254: 1, 255: 1, 0: 1};
        var m = mask.split('.');
        for (var i = 0; i <= 3; i++) {
            if (!(m[i] in correct_range)) {

                $(this).val('');
                $(this).attr('title', error);
                // $(this).css('background', 'pink');
                $(this).css("border", 'pink 2px solid');
                return false;
                break;
            }
        }
        if ((m[0] == 0) || (m[0] != 255 && m[1] != 0) || (m[1] != 255 && m[2] != 0) || (m[2] != 255 && m[3] != 0)) {
            $(this).val('');
            $(this).attr('title', error);
            // $(this).css('background', 'pink');
            $(this).css("border", 'pink 2px solid');
            return false;
        } else {

            // $('.netmask').val($(this).val());
            // $('.netmask').trigger('change');
            $(this).attr('title', 'The netmask is correct.');
            //  $(this).css('background', '#82FA58');
            $(this).css("border", '#82FA58 2px solid');
        }


    });
    /**
     *
     * @type event
     * Permits only valid keystrokes
     * numbers and dot
     */
    $(document.body).on('keypress', '.ipaddress, .netmask, .gateway, .ciscosw, .subnet', function (e) {

        var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
        if (keyCode != 46 && keyCode > 31 && (keyCode < 48 || keyCode > 57))
            return false;
        return true;
    });
    $(document.body).on('change', '.ciscosw', function (e) {
        e.preventDefault();
        var value = $(this).val();
        if (value != 24 && value != 48) {

            $(this).attr('title', 'Only 24 and 48 are accepted');
            $(this).css("border", 'pink 2px solid');
            $(this).val('48');
            return false;


        } else {
            $(this).attr('title', 'The entry is ok.');
            $(this).css("border", '#82FA58 2px solid');
        }
    });

    $(document.body).on('change', '.range', function (e) {
        var error = "RANGE format is xx-xx.";
        var splitted = $(this).val().split('-');
        console.log(splitted);
        if (splitted.length != 2) {
            $(this).val('');
            $(this).attr('title', error);
            // $(this).css('background', 'pink');
            $(this).css("border", 'pink 2px solid');
            return false;

        } else {
            for (var i = 0; i <= 1; i++) {
                if ($.isNumeric(splitted[i])) {
                    $(this).attr('title', 'The range is correct.');
                    //  $(this).css('background', '#82FA58');
                    $(this).css("border", '#82FA58 2px solid');
                } else {
                    $(this).attr('title', 'The value ' + splitted[i] + ' in range  is not a number');
                    // $(this).css('background', 'pink');
                    $(this).css("border", 'pink 2px solid');
                    $(this).val('');
                    return false;
                    break;
                }
            }
        }
    });



    $(document.body).on('change', '.vlans', function (e) {

        var splitted = $(this).val().split(',');
        var len = splitted.length;
        for (var i = 0; i < len; i++) {
            if ($.isNumeric(splitted[i])) {
                $(this).attr('title', 'The range is correct.');
                //  $(this).css('background', '#82FA58');
                $(this).css("border", '#82FA58 2px solid');
            } else {
                $(this).attr('title', 'The value ' + splitted[i] + ' in range  is not a number');
                // $(this).css('background', 'pink');
                $(this).css("border", 'pink 2px solid');
                $(this).val('');
                return false;
                break;
            }

        }

    });


});

function IPAddressKeyOnly(e) {
    var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
    if (keyCode != 46 && keyCode > 31 && (keyCode < 48 || keyCode > 57))
        return false;
    return true;
}


function checkUniq(field, value)
{
    var req = new AjaxRequest();
    req.setMethod('POST');
    var params = "table=custip&field=" + encodeURIComponent(field) + "&value=" + encodeURIComponent(value);
    req.loadXMLDoc("includes/checkuniq.php", params);
}

function checkUniq_subnet(field, value)
{
    var req = new AjaxRequest();
    req.setMethod('POST');
    var params = "table=custip&field=" + encodeURIComponent(field) + "&value=" + encodeURIComponent(value) + "&subnet=" + encodeURIComponent(document.getElementById("subnet").value);
    req.loadXMLDoc("includes/checkuniq_subnet.php", params);
}
function checkUniq_all(field)
{
    var req = new AjaxRequest();
    req.setMethod('POST');
    try {
        var params = "table=custip&field=" + encodeURIComponent(field) + "&netmask=" + encodeURIComponent(document.getElementById("netmask").value) + "&subnet=" + encodeURIComponent(document.getElementById("subnet").value);
        req.loadXMLDoc("includes/checkuniq_all.php", params);
    } catch (err) {
//   Block of code to handle errors
    }
}


function check_range(value)
{
    var req = new AjaxRequest();
    req.setMethod('POST');
    var params = "value=" + encodeURIComponent(value);
    req.loadXMLDoc("includes/check_range.php", params);
}
function check_range_checkbox(value)
{
    var req = new AjaxRequest();
    req.setMethod('POST');
    var params = "value=" + encodeURIComponent(value);
    req.loadXMLDoc("includes/check_range_checkbox.php", params);
}







function utf8_to_b64(str) {
    return window.btoa(unescape(encodeURIComponent(str)));
}

function b64_to_utf8(str) {
    return decodeURIComponent(escape(window.atob(str)));
}

/*
 This script is identical to the above JavaScript function.
 */
var ct = 1;

function new_link()
{
    ct++;
    var div1 = document.createElement('div');
    div1.id = ct;

    // link to delete extended form elements
    var delLink = '<div style="text-align:right;margin-right:65px"><a href="javascript:delIt(' + ct + ')">Del</a></div>';

    div1.innerHTML = document.getElementById('newlinktpl').innerHTML + delLink;

    document.getElementById('newlink').appendChild(div1);

}
// function to delete the newly added set of elements
function delIt(eleId)
{
    d = document;

    var ele = d.getElementById(eleId);

    var parentEle = d.getElementById('newlink');

    parentEle.removeChild(ele);

}


