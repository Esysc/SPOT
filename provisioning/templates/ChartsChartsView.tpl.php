<?php
$this->assign('title', 'SPOT | App overview');
$this->assign('nav', '');

$this->display('_Header.tpl.php');

$yearBase = "2014";

$RADMIN = json_decode(apiWrapper('http://' . GlobalConfig::$SYSPROD_SERVER->MGT . '/SPOT/provisioning/api/tempdatas?filter=RADMIN'))->rows[0];
$radminStock = $RADMIN->message;
$radminProcessed = $RADMIN->dwprocessed;
$radminKey = $RADMIN->data;
// Get total results from stored provisioned machines trough this gui
$WINDOWS = json_decode(apiWrapper('http://' . GlobalConfig::$SYSPROD_SERVER->MGT . '/SPOT/provisioning/api/provisioningactions?os=WINDOWS'));
$AIX = json_decode(apiWrapper('http://' . GlobalConfig::$SYSPROD_SERVER->MGT . '/SPOT/provisioning/api/provisioningactions?os=AIX'));
$LINUX = json_decode(apiWrapper('http://' . GlobalConfig::$SYSPROD_SERVER->MGT . '/SPOT/provisioning/api/provisioningactions?os=LINUX'));
$totWinPro = $WINDOWS->totalResults;
$totAixPro = $AIX->totalResults;
$totLinPro = $LINUX->totalResults;

// Get total results from stored provisioned machines in the dashboard
$WINDOWS = json_decode(apiWrapper('http://' . GlobalConfig::$SYSPROD_SERVER->MGT . '/SPOT/provisioning/api/provisioningnotificationses?filter=microsoft'));
$AIX = json_decode(apiWrapper('http://' . GlobalConfig::$SYSPROD_SERVER->MGT . '/SPOT/provisioning/api/provisioningnotificationses?filter=OpenFirmware'));
$LINUX = json_decode(apiWrapper('http://' . GlobalConfig::$SYSPROD_SERVER->MGT . '/SPOT/provisioning/api/provisioningnotificationses?filter=REDHAT'));
$totWinDas = $WINDOWS->totalResults;
$totAixDas = $AIX->totalResults;
$totLinDas = $LINUX->totalResults;
?>
<style>
    #green {
        color: green;
    }
    #red {
        color: red;
    }
</style>
<script>
    $('document').ready(function () {

        var mdt = '<?php echo GlobalConfig::$SYSPROD_SERVER->MDT; ?>';
        var drbl = '<?php echo GlobalConfig::$SYSPROD_SERVER->DRBL; ?>';
        var radminAnswer;
        var stockLic;
        var usedLic;
        var servers = {
            0: mdt,
            1: drbl
        };
        function getNumbers(inputString) {
            var regex = /\d+\.\d+|\.\d+|\d+/g,
                    results = [],
                    n;

            while (n = regex.exec(inputString)) {
                results.push(parseFloat(n[0]));
            }

            return results;
        }
        function radminQuery(lickey) {
            $('.radminQuery').html('<img src="/SPOT/provisioning/images/loader.gif" alt="Contacting radmin DB" title="Contacting radmin DB"/>');
            $.ajax({
                url: "includes/checkRadmin.php",
                type: "post",
                data: 'lickey=' + lickey,
                cache: false,
                async: true,
                success: function (data) {

                    $('.radminQuery').html(data);
                    radminAnswer = getNumbers($(data).text());
                    if (typeof radminAnswer !== 'string' && radminAnswer.length != 0) {

                        var act = radminAnswer[1] - radminAnswer[2];
                        RadminChart(radminAnswer[1], act, radminAnswer[2], '#radmin', '#legend');
                        radmin(radminAnswer[1], true, lickey, radminAnswer[2], false);
                        $('#licnum').val(radminAnswer[1]);
                        var divclass = 'alert alert-success';
                        if (radminAnswer[2] <= 5)
                            divclass = 'alert alert-danger';
                        $('#response').html('<div class="' + divclass + '"><strong>Number of activated hosts: ' + act + ' Number of available license: ' + radminAnswer[2] + '. Check performed considering ' + radminAnswer[1] + ' stock license<span class="pull-right"><u>&reg; Radmin source</u></span></strong></div>');

                    }



                }

            });
        }

        var serializedData = JSON.stringify(servers);
        $.ajax({
            url: "includes/ping.php",
            type: "post",
            data: 'hosts=' + serializedData,
            cache: false,
            async: true,
            beforeSend: function (data) {
                $('#alive').html('Checking Sysprod Servers recheability   <img src="/SPOT/provisioning/images/loader.gif" />');
            },
            success: function (data) {

                var alive = JSON.parse(data);
                var html = '';
                for (var key in alive) {
                    if (alive.hasOwnProperty(key)) {
                        html = html + ' ' + alive[key];
                    }
                }

                $('#alive').html(html);
            }
        });

        var totWinPro = <?php echo $totWinPro; ?>;
        var totAixPro = <?php echo $totAixPro; ?>;
        var totLinPro = <?php echo $totLinPro; ?>;
        var totPro = totWinPro + totAixPro + totLinPro;
        var totWinDas = <?php echo $totWinDas; ?>;
        var totAixDas = <?php echo $totAixDas; ?>;
        var totLinDas = <?php echo $totLinDas; ?>;
        var totDas = totWinDas + totAixDas + totLinDas;

        LoadChart(totAixPro, totWinPro, totLinPro, totPro, '#dvChart', '#dvLegend');

        LoadChart(totAixDas, totWinDas, totLinDas, totDas, '#dashChart', '#dashLegend');

        function LoadChart(aixtot, wintot, lintot, tot, diva, divb) {
            $(diva).html("");
            $(divb).html("");
            //Populate data for the chart
            var os = new Array();
            var aix = {};
            aix.text = "Aix : " + aixtot;
            aix.value = aixtot;
            aix.color = "#FEFD01";
            aix.label = "AIX OS";


            os.push(aix);
            var win = {};
            win.text = "Windows : " + wintot;
            win.value = wintot;
            win.color = "#0040FF";
            win.label = "Microsoft OS";
            os.push(win);



            var lin = {};
            lin.text = "Linux : " + lintot;
            lin.value = lintot;
            lin.color = "#00FF00";
            lin.label = "Redhat OS";


            os.push(lin);

            var el = document.createElement('canvas');
            $(diva)[0].appendChild(el);

            var ctx = el.getContext('2d');


            var options = {
                segmentShowStroke: true,
                segmentStrokeColor: "#fff",
                segmentStrokeWidth: 2,
                percentageInnerCutout: 50,
                animation: true,
                animationSteps: 100,
                animationEasing: "easeOutBounce",
                animateRotate: false,
                animateScale: false,
                onAnimationComplete: false,
                labelFontFamily: "Arial",
                labelFontStyle: "normal",
                labelFontSize: 24,
                labelFontColor: "#666",
            };


            //var chart = new Chart(ctx).Pie(os, options);
            var chart = new Chart(ctx).Pie(os);
            var width = 500;
            for (var i = 0; i < os.length; i++) {
                ctx.font = options.labelFontStyle + " " + options.labelFontSize + "px " + options.labelFontFamily;
                ctx.fillStyle = 'black';
                ctx.textBaseline = 'middle';


                ctx.fillText(os[i].text + "%", width / 2 - 20, width / 2, 200);
                var div = $("<div />");
                div.css("margin-bottom", "10px");
                div.html("<span style = 'display:inline-block;height:10px;width:10px;background-color:" + os[i].color + "'></span> " + os[i].text);
                $(divb).append(div);
            }
            $(divb).append('Total: ' + tot);


        }

        function RadminChart(licnum, acttot, diff, diva, divb) {
            $(diva).html("");
            $(divb).html("");
            //Populate data for the chart
            var radmin = new Array();
            var lic = {};
            lic.text = "License available: " + diff;
            lic.value = diff;
            lic.color = "#FEFD01";
            lic.label = "License available";


            radmin.push(lic);
            var act = {};
            act.text = "Hosts activated: " + acttot;
            act.value = acttot;
            act.color = "#0040FF";
            act.label = "Hosts activated";
            radmin.push(act);


            var el = document.createElement('canvas');
            $(diva)[0].appendChild(el);

            var ctx = el.getContext('2d');


            var options = {
                segmentShowStroke: true,
                segmentStrokeColor: "#fff",
                segmentStrokeWidth: 2,
                percentageInnerCutout: 50,
                animation: true,
                animationSteps: 100,
                animationEasing: "easeOutBounce",
                animateRotate: false,
                animateScale: false,
                onAnimationComplete: false,
                labelFontFamily: "Arial",
                labelFontStyle: "normal",
                labelFontSize: 24,
                labelFontColor: "#666",
            };


            //var chart = new Chart(ctx).Pie(os, options);
            var chart = new Chart(ctx).Pie(radmin);
            var width = 500;
            for (var i = 0; i < radmin.length; i++) {
                ctx.font = options.labelFontStyle + " " + options.labelFontSize + "px " + options.labelFontFamily;
                ctx.fillStyle = 'black';
                ctx.textBaseline = 'middle';


                ctx.fillText(radmin[i].text + "%", width / 2 - 20, width / 2, 200);
                var div = $("<div />");
                div.css("margin-bottom", "10px");
                div.html("<span style = 'display:inline-block;height:10px;width:10px;background-color:" + radmin[i].color + "'></span> " + radmin[i].text);
                $(divb).append(div);
            }
            $(divb).append('Stock license ' + licnum);


        }

        var licnum = $('#licnum').val();
        var lickey = $('#lickey').val();
        var response = '';

        radmin(licnum, false, lickey, false,true);
        function radmin(licnum, reset, lickey, dwprocessed, update) {
            //Update the db within new value
            var tempurl = 'http://<?php echo $_SERVER['SERVER_NAME']; ?>/SPOT/provisioning/api/tempdata/RADMIN';

            var lic = {'message': licnum};
            // This means we are inserting a new value
            if (reset == true) {
                var currentdate = new Date();
                var datetime = currentdate.getDate() + "-"
                        + (currentdate.getMonth() + 1) + "-"
                        + currentdate.getFullYear() + " "
                        + currentdate.getHours() + ":"
                        + currentdate.getMinutes() + ":"
                        + currentdate.getSeconds();
                lic.timestamps = datetime;
                lic.data = lickey;
                lic.dwprocessed = dwprocessed;
            }
            $.ajax({
                url: tempurl,
                type: 'PUT',
                data: JSON.stringify(lic),
                success: function (data) {
                    if (reset == true) {

                    }

                    var date = data.timestamps;

                    $('.date').html('<p class="pull-right"><small><span class="icon-time"></span> Last update of stock license occured ' + date + '</small></p>');
                }
            });

            var url = 'http://<?php echo GlobalConfig::$SYSPROD_SERVER->DRBL; ?>/Logs/RadminActServerLog/index.php';
            var send = 'limit=' + licnum + '&reset=' + reset;
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'jsonp',
                jsonp: "callback",
                data: send,
                success: function (data)
                {
                    var diff;
                    if (typeof <?php echo $radminProcessed; ?> !== 'undefined') {
                        diff = <?php echo $radminProcessed; ?>;
                        response = <?php echo $radminStock; ?> - diff;
                    } else {
                        var rep = JSON.parse(data);
                        response = rep.total;
                        diff = licnum - response;
                    }
                    var divclass = 'alert alert-success';
                    if (diff <= 5)
                        divclass = 'alert alert-danger';
                    if (typeof update !== 'undefined' && update == true) {
                        RadminChart(licnum, response, diff, '#radmin', '#legend');
                        $('#response').html('<div class="' + divclass + '"><strong>Number of activated hosts: ' + response + ' Number of available license: ' + diff + '. Check performed considering ' + licnum + ' stock license<span class="pull-right"><u>Approximated from local source</u></span></strong></div>');
                    }
                },
                error: function (data) {
                }
            });
        }

        $('#check').on('click', function (e) {
            e.preventDefaults;
            licnum = $('#licnum').val();
            lickey = $('#lickey').val();
            radmin(licnum, false, lickey,false, true);
            radminQuery(lickey);



        }
        );

        $('#reset').on('click', function (e) {
            e.preventDefaults;
            $('#confirm').remove();
            $('#servermsg').html('Are you sure ? The log file will be cleaned.');
            $('.modal-footer').append('<p id="confirm" class="btn  btn-info">Proceed</p>');
            $('#basicModal').modal();
            $('#confirm').on('click', function () {
                $('#close').trigger('click');
                licnum = $('#licnum').val();
                lickey = $('#lickey').val();
                radmin(licnum, true, lickey, false, true);
                radminQuery(lickey);
            });
        })

        radminQuery(lickey);
     //   $('#sysmon').trigger('click');
    });
</script>
<div class="container">

    <h1>
        <i class="icon-th-list"></i>  <?php echo $this->title; ?>
<!--    <span id=loader class="loader progress progress-striped active"><span class="bar"></span></span> -->

    </h1>

    <!-- underscore template for the collection -->
    <h2 class="alert alert-info" role="alert"><i class="icon-cloud"></i> SPOT Charts</h2>
    <!-- underscore template for the collection -->


    <span class="icon icon-smile">

    </span> More <a href="/SPOT/provisioning/stats/" >charts</a>   





    <table  class="table pull-left">
        <tr>
            <th colspan="2">
        <center>Total machines provisioned trough this UI</center>
        </th>
        <th colspan="2">
        <center>Total machines logged in dashboard</center>
        </th>
        </tr>
        <tr>
            <td>
                <div id="dvChart">
                </div>
            </td>
            <td>
                <div id="dvLegend">
                </div>
            </td>
            <td>
                <div id="dashChart">
                </div>
            </td>
            <td>
                <div id="dashLegend">
                </div>
            </td>
        </tr>
    </table>




    <table   class="table">
        <tr>
            <th colspan="3">

        <center>
            Radmin activation Count . The count is done parsing the AS log file. Please align the value within the Radmin answer below:<span id="alive" class="pull-right"></span>
            <div class="radminQuery"></div>
        </center>
        </th>
        </tr>
        <tr>

            <td id="radmin">

            </td>
            <td id="legend">

            </td>
            <td>
                <label for="licnum">
                    Number of license you want to check. Go to <a href="https://www.radmin.com/support/activationscheck.php" target="_blank" >Radmin check site</a> and check the value after inserting the license key.
                </label>
                <input type="text" style="width:50px;" value="<?php echo $radminStock; ?>" title="Put the number of license available" id="licnum" />  
                <p> <label for="lickey">
                        License key used for check.
                    </label>
                    <input type="text" style="width:350px;" placeholder= "RADPR-XXXXXX-XXXXXXXX-XXXXXXXX" value="<?php echo $radminKey; ?>" title="License key" id="lickey" />
                </p>
                <div class="date"></div>

                <p class="breadcrumb"><small>
                        The stock value is read from Famatech answer and stored automatically in local DB. If Famatech is not available, only local source will be displayed.

                    </small></p>
                <label for="check">
                    Check again the activation count
                </label>
                <button class="btn btn-info btn-mini" id="check">
                    Check Again
                </button>
                <label for="reset">
                    Reset the activation count and store the license key
                </label>
                <button class="btn btn-info btn-mini" id="reset">
                    Reset Activation Count
                </button>
            </td>

        </tr>
        <tr>
            <td id="response" colspan="3">

            </td>
        </tr>
    </table>


</div> <!-- /container -->


<?php
$this->display('_Footer.tpl.php');
?>
