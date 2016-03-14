<?php

$this->assign('title', 'SPOT | Memos');
$this->assign('nav', 'memo');

$this->display('_Header.tpl.php');
?>
<script>
    $(document).ready(function () {
        //check if HQ servers  are all alive before to continue

        var ist = 'ist.my.compnay.com';
        var servers = {
            1: ist
        };
        var serializedData = JSON.stringify(servers);
        $.ajax({
            url: "includes/ping.php",
            type: "post",
            data: 'hosts=' + serializedData,
            cache: false,
            async: true,
            beforeSend: function (data) {
                $('#ping').html('<small>Checking ist rest interface   <img src="/SPOT/provisioning/images/loader.gif" /></small>');
            },
            success: function (data) {

                var alive = JSON.parse(data);
                var html = '';
                for (var key in alive) {
                    if (alive.hasOwnProperty(key)) {
                        html = html + ' ' + alive[key];
                    }
                }
                $('#ping').html(html);
            }

        });


        $('#details').on('click', function () {
            $('#basicModal').show();
        });
        $('.release').hide();
        $('.results').hide();
        $('.save').hide();
        // Get all the sales order in the tblprogress table
        $.get("/SPOT/provisioning/api/tblprogresses", function (jsonResult) {
            var Jdata = jsonResult.rows;
            console.log(Jdata);
            // $('#salesel').attr('enabled', 'true');
            $.each(Jdata, function (i, o) {

                var Jfield = JSON.parse(o.data);
                if (Jfield.completed == true) {
                    $('#salesel').append(
                            '<option>' + o.salesorder + '</option>'

                            );
                    $('#salesel').chosen().trigger("chosen:updated");
                }
            });
        });
        $('*[required]').before("<span class='icon-star' style='color:red'></span>");
        $('#msg').hide();
        $('#salesel').on('change', function () {
            if ($('#salesel').val() !== '') {
                $('.release').show();
                $.get("/SPOT/provisioning/api/tblprogresses?Salesorder_Equals=" + $('#salesel').val(), function (Result) {
                    console.log(Result);
                    var row = Result.rows;
                    console.log(row);
                    var datas = row[0].data;
                    var jdatas = JSON.parse(datas)
                    console.log(jdatas);
                    if (typeof jdatas.releasename !== 'undefined') {
                        $('#release').val(jdatas.releasename).trigger('keyup');
                    }
                });
            } else {
                $('#release').val('').trigger('keyup');
                $('.release').hide();
            }
        });
        $('#release').on('keyup', function () {
            if ($('#release').val() !== '') {
                $('.save').show();
            } else {
                $('.save').hide();
            }
        });
        $('.save').on('click', function (e) {
            e.stopPropagation()
            e.preventDefault();
            if ($('*[required]').val() === '') {

                $('#msg').hide();
                $('#msg').html("Please fill all the required fields")

                $('#msg').show();
                setTimeout(function () {
                    $('#msg').fadeOut(2000);
                }, 3000);
                console.log($('*[required]'));
                return false;
            } else {

                $('#msg').hide();
                //  $release = $xml.find( "HECustomerProductRelease" );

                var release = $('#release').val();
                var releaseSplitted = release.split(',');
                var releaselength = releaseSplitted.length - 1;
                $.each(releaseSplitted, function (index, value) {

                    value = $.trim(value);
                    if (value !== '') {

                        $.ajax({
                            url: 'includes/loadMemo.php',
                            data: {release: value},
                            beforeSend: function (xhr) {
                                $('.results').show();
                                $('.sendcheck').hide();
                                $('.save').hide();
                                $('#alive').html('Contacting IST....  for release(s) ' + release + ' <img src="/SPOT/provisioning/images/loader.gif" />');
                            },
                            type: 'POST',
                            success: function (data, textStatus, jqXHR) {
                                if (index == releaselength) {
                                    $('#alive').html('');
                                    $('.sendcheck').show();
                                }
                                parse(data, value);
                            },
                            error: function (err) {
                                $('.sendcheck').hide();
                                $('.' + value).remove();
                                $('#msg').text(err + '\n');
                                $('#msg').show();
                            }
                        });
                    }
                });
            }
            function parse(document, release) {
                console.log(document);
                try {
                    var formatted = 5; //Number of column we want in the resulting html table
                    var html = "<table class='table-bordered table-responsive table table-striped " + release + "'><tr><th colspan='" + formatted + "'>Memos found for release:  " + release + "</th></tr>";
                    var counter = 0;
                    var subcounter = 0;
                    var $doc = $(document).find("Memo");
                    var doclength = $doc.length;
                    var colspan = '';
                    $doc.each(function () {
                        if ((counter % formatted) == 0)
                        {
                            html = html + '<tr>';
                            subcounter = 4;
                        }
                        // this is where all the reading and writing will happen
                        //      $(this).attr('');
                        var $ele = $(this);
                        var number = $ele.find('number').text();
                        var id = $ele[0].id;
                        id = id.replace(/[^0-9]/g, '');
                        number = number.replace(/[^0-9]/g, '');
                        if (counter == (doclength - 1)) {
                            var span = doclength % 5;
                            colspan = 'colspan="' + span + '"';
                        }
                        html = html + '<td ' + colspan + '><label class="checkbox" for="memo' + number + '"><input type="checkbox" name="memo' + number + '" value="' + number + '" id="memo' + id + '" class="memo"/><a href="#" class="link" legend="' + number + '" memoid="' + id + '">Memo Id: ' + id + '<br /> Memo Num: ' + number + '</a></label><p class="btn btn-mini details" memonumber="' + number + '">Get details<p></td>'

                        if ((subcounter % 5) == 0)
                        {
                            html = html + '</tr>';
                        }
                        subcounter--;
                        counter++;
                    });
                    html = html + '<table>';
                    $('#results').append(html);
                } catch (err) {
                    //   $('.results').hide();
                    $('.sendcheck').hide();
                    $('.' + release).remove();
                    // was not XML
                    $('#msg').text(err + '\n' + document);
                    $('#msg').show();
                }



            }


        });
        $('body').on('click', 'a.link', function (e) {

            e.stopPropagation();
            e.preventDefault();
            var url = 'http://ist.my.compnay.com/cgi-bin/WebObjects/ist.woa/wa/inspectRecord?entityName=Memo&id=';
            var id = $(this).attr('memoid');
            url = url + id;
            window.open(url);
            /*   $.ajax({
             url: url,
             type: 'POST',
             data: {
             number: number,
             bookmarkName: bookmarkName,
             Search: Search
             },
             success: function (data, textStatus, jqXHR) {
             console.log(data);
             },
             error: function(err) {
             console.log(err);
             }
             
             }); 
             */


        });
        $('body').on('click.detail', 'p.details', function (e) {

            e.stopPropagation();
            e.preventDefault();
            var $ele = $(this);
            $ele.off('click.detail');
            var url = 'includes/loadMemoXmlDetail.php';
            var memoNumber = $(this).attr('memonumber');
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    memoNumber: memoNumber

                },
                beforeSend: function (xhr) {


                    $ele.html('Contacting IST....  for detail on memo ' + memoNumber + ' <img src="/SPOT/provisioning/images/loader.gif" />');
                },
                success: function (data, textStatus, jqXHR) {
                    var $doc = $(data);
                    var title = $doc.find('title').text();
                    var condition = $doc.find('condition').text();
                    var workaround = $doc.find('workaround').text();
                    $ele.html('<p><strong>Title: </strong>' + title + '</p><p><strong>Condition: </strong>' + condition + '</p><p><strong>Workaround: </strong>' + workaround + '</p>');
                },
                error: function (err) {
                    console.log(err)
                    $('#msg').text(err + '\n');
                    $('#msg').show();
                }

            });
        });
        $('.sendcheck').on('click', function () {
            /*
             * update the sales order report to add checked memo
             */
            var salesorder = $('#salesel').val();
            var JSONdata;
            var i = 0;
            var Jdata = {};
            $.ajax({
                url: "/SPOT/provisioning/api/tblprogresses?filter=" + salesorder,
                type: 'GET',
                success: function (data) {
                    Jdata = JSON.parse(data.rows[0].data);
                    var sales_id = data.rows[0].id;
                    if (!Jdata.hasOwnProperty('memos')) {
                        Jdata.memos = {};
                    }
                    $('.memo:checked').each(function (index) {
                        var $ele = $(this);
                        var $p = $ele.nextAll('table:first');
                        var num = $ele.val();
                        var id = $ele.attr('id');
                        id = id.replace(/[^0-9]/g, '');
                        i++;


                        var url = 'http://ist.my.compnay.com/cgi-bin/WebObjects/ist.woa/wa/inspectRecord?entityName=Memo&id=';
                        url = url + id;
                        Jdata.memos[i] = {memoNum: num, memoId: id, memoUrl: url};



                    });
                    console.log(Jdata);

                    /*
                     * Send to update table
                     */
                    JSONdata = JSON.stringify(Jdata);
                    var toSend = {data: JSONdata};
                    var stringSend = JSON.stringify(toSend);
                    $.ajax({
                        type: "PUT",
                        url: "/SPOT/provisioning/api/tblprogress/" + sales_id,
                        data: stringSend,
                        success: function () {
                            $('#servermsg').html('Successfully updated order report within memos');
                            $('#basicModal').modal();
                        }

                    });
                }

            }); // Ajax  request

            /*
             * Ok, Memos prepared, now add it to the order report
             */


        });
    });
</script>

<div class="container">

    <h1>
        <i class="icon-th-list"></i> Memos <span class="pull-right" id="ping"></span>
    </h1>
    <p id="msg" class="alert alert-error"></p>

    <!-- underscore template for the collection -->

    <table class="stselection table-bordered table-responsive table table-striped">

        <tr class="salesel">
            <th>
                <label for="salesel"><strong>
                        Select a stored SO
                    </strong>
                </label>
            </th>

            <td>
                <select class="chosen" id="salesel" name="salesel" required autofocus="autofocus">
                    <option value="">
                        Select a sales order
                    </option>

                </select>
            </td>

        </tr>










        <tr class="release">
            <th>
                Release(s):
                <br />Only HE releases are supported, you can put severals separated by comma.
            </th>
            <td>
                <input name="release" id="release" required class="form-control" type="text" />

            </td>

        </tr>


        <tr class='save'>
            <td colspan="2">


                <input type="submit" class="save btn btn-primary pull-right" value="Send Request" />

            </td>
        </tr>


    </table>


    <table class="table table-bordered table-striped table-responsive results">
        <tr>
            <th>
                <label for="results"> <strong> Results </strong></label>
            </th>
        </tr>
        <tr>
            <td id="results">
                <div id="alive">

                </div>

            </td>
        </tr>
        <tr>
            <td>
                <button class="btn btn-mini btn-success pull-right sendcheck">Save Status</button>
            </td>
        </tr>

    </table>
</div> <!-- /container -->

<?php

$this->display('_Footer.tpl.php');
?>