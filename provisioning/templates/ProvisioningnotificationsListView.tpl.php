<?php
$this->assign('title', 'SPOT | Dashboard');
$this->assign('nav', 'Dashboard');

$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
    $LAB.script("scripts/app/provisioningnotificationses.js").wait(function () {
        $(document).ready(function () {

            page.init();
        });
        // hack for IE9 which may respond inconsistently with document.ready
        setTimeout(function () {
            if (!page.isInitialized)
                page.init();
        }, 1000);
    });</script>
<script>
<?php GlobalConfig::$SYSPROD_SERVER->MGT; ?>
    $(document).ready(function () {

        $('body').on('click', '.command', function (e) {
            //e.preventDefault();
            var COMMANDURI = $(this).attr('url');

            $.ajax({
                url: COMMANDURI,
                method: 'GET',
                success: function (data) {
                    $('.results').html('<pre>' + data.returnstdout + '</pre>');
                },
                error: function (data) {
                    console.log(data);
                    $('.results').html('<pre>The log has been already removed</pre>');
                }

            });

        });

    });
</script>
<style>
    .showprogress {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: -o-pre-wrap;
        word-wrap: break-word;

        white-space: -moz-pre-wrap;
        white-space: -pre-wrap;
        width: 30%;
    }

    table {
        font-size: x-small;
    }
    .containerDash {
        position:absolute;
        left:10px;
    }
    img {
        width: auto;
        height: 40px;
    }


</style>
<div class="containerDash">

    <h1 style="position:relative;">
        <i class="icon-th-list"></i> Provisioning Dashboard 
        <span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
        <b id="filternow" class="label label-inverse"></b>       
        <span class='input-append pull-right searchContainer'>


            <input id='filter' type="text" placeholder="Search..." />
            <button class='btn add-on'><i class="icon-search"></i></button>
        </span>
    </h1>

    <?php
    if (isset($_SESSION['salesorder'])) {
        ?>
        <script>

            $('#filter').val('<?php echo $_SESSION['salesorder']; ?>');




        </script>

        <?php
    }
    ?>


    <!-- underscore template for the collection -->
    <script type="text/template" id="provisioningnotificationsCollectionTemplate">
        <%=  view.getPaginationHtml(page) %>

        <table class="collection table table-bordered table-hover">
        <thead>
        <tr>
        <th colspan="11"><center>
        <span class="label label-warning " ><i class="icon-time"></i> Show a in progress job</span>
        <span class="label label-success"  ><i class="icon-suitcase"></i> Show a successfully completed job</span>
        <span class="label label-important "><i class="icon-tasks"></i> Show a timeout of more than 2 hours between now and last update</span>
        <span class="label label-info" ><i class="icon-hand-up"></i> The task takes some time (more than 2 hours between start and last update</span>
        </center></th>
        </tr>
        <tr>
        <th id="header_Notifid">[Salesorder] [Rackshelf/IP]<% if (page.orderBy == 'Notifid') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Hostname">Hostname<% if (page.orderBy == 'Hostname') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Installationip">Installation Ip<% if (page.orderBy == 'Installationip') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Configuredip">Postinstall IP<% if (page.orderBy == 'Configuredip') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Startdate">Start Time<% if (page.orderBy == 'Startdate') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Update">Last Update<% if (page.orderBy == 'Update') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th> Minutes elapsed</th>

        <th id="header_Progress" >Progress&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<% if (page.orderBy == 'Progress') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Image">Image/Task ID<% if (page.orderBy == 'Image') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Os" >Os Version<% if (page.orderBy == 'Os') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Firmware" >Hardware<% if (page.orderBy == 'Firmware') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>



        </tr>
        </thead>
        <tbody>
        <% items.each(function(item) { %>
        <%
        var d3 = new Date()
        var d2 = new Date(_date(app.parseDate(item.get('update'))))
        var d1 = new Date(_date(app.parseDate(item.get('startdate'))))
        var diff2 = (d2-d1)/1000
        var diff1 = (d3-d2)/1000
        var delta = diff2
        var deltanow = diff1
        // calculate (and subtract) whole days
        var days = Math.floor(delta / 86400);
        delta -= days * 86400;
        var daysnow = Math.floor(deltanow / 86400);
        deltanow -= daysnow * 86400;

        // calculate (and subtract) whole hours
        var hours = Math.floor(delta / 3600) % 24
        delta -= hours * 3600
        var hoursnow = Math.floor(deltanow / 3600) % 24
        deltanow -= hoursnow * 3600
        // calculate (and subtract) whole minutes
        var minutes = Math.floor(delta / 60) % 60
        delta -= minutes * 60
        var minutesnow = Math.floor(deltanow / 60) % 60
        deltanow -= minutesnow * 60

        // what's left is seconds
        var seconds = delta % 60  // in theory the modulus is not required
        var secondsnow = parseInt(deltanow % 60)  // in theory the modulus is not required
        var activeClass = 'progress-striped active';
        %>
        <% if ( _.escape(item.get('progress')) == 100 ) {
        var trClass = "success"
        var spanClass = "label label-success"
        var iconClass = "icon-suitcase"
        var barClass = "bar-success"
        var activeClass = '';

        } else {

        if ( diff1 > 7200 ) {
        var trClass = "error"
        var spanClass = "label label-danger"
        var iconClass = "icon-tasks"
        var barClass = "bar-danger"
        } else {
        var trClass = "warning"
        var spanClass = "label label-warning"
        var iconClass = "icon-time"
        var barClass = "bar-warning"
        if ( diff2 > 7200 ) {
        var trClass = "info"
        var spanClass = "label label-info"
        var iconClass = "icon-hand-up"
        var barClass = "bar-info"

        }
        } 
        }%>

        <tr id="<%= _.escape(item.get('notifid')) %>" class="<%= trClass %>">
        <td><span class="<%= spanClass %>"><icon class="<%= iconClass %>"></i></span><br /><%= _.escape(item.get('notifid') || '') %>
        <% img = '<img  class=*AIX" title="AIX" alt="AIX" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQ8AAADGCAYAAADMvwX2AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAUPVJREFUeF7tnQe4VMXZx69wqQJiQ3qRKl1E0MQkpqlJTIzRGAuiYm+xJhZsKCJIE6WJIE2QJr33cq/0jlQBQVTArjHGlO/93v+cM7tzZueU3bu7tzD7PPPce3fPmXP23DO/83/LvHPSV3un5NiXvQL2CtgrkPQVADxss9fA3gP2Hkj2HrDgsPC094C9B1K6B1LaKVlC2e3tU83eAyXvHrDwsE8dew/YeyCleyClnexTpOQ9RbL1P100qQf163YHPX7/NbYVoWswZXhX2pM/nJK5Dyw87FMnK/cAbs7KlSrSKVVOpgoVyhF79m0rQtdA/F/Kl6Pz2zaJDJGs3DjJ0MxuW/JUTaerfyGgYYFRPIB5WtXKtGpGn1AVYuFhlUdG74GhL99PVS04ih04zzitCh3aMDoQIBm9cayKKHkqIpn/KWzos86sWuwGjlVIOVQmtzRd/uuOFh7J3PB22/QBb0T/h9jPUcHCowj5NpIBYy4DJGg8WOVhzZaM3QO3Xn9pxsBxcZdT6KZXz4q1v/Q4k+qfW954vOqNy9Klfz01ti1+17dtf0Ul8XntFonO3Av+XFl8hm30wdfsJxXEZ2fULSM+w7HU81J/V/dt+cuKCeeP98IGN87vgck1qWr13Ni2OL8rnjydsD9+oo+rnjuDKp1WOrS/oONVqVwx0PeRsRvHPsHT9wQvrtcSnvuwwZDq5xhAj82rTb979DQCOPD7s6vqEgazPki7LqkjBhy2w6C6d1wNsS0GndwWMMF7gJJ+TnL7W4eelfAZBiuOnVv2JPGZ7Af76BBR+5X74Xj4DvIYv7zL38y76IYq4hwfmVFLQErtD/0AKAAj+sZ3VQGTynWGk3v22G6+6sPCwyqPjN0Drc6pn1F4YHDKQYGnLCBx55vVY+9h8OA9DPrylUrF3sdAv6FPNTEQpdLAe4CAuj/6Rh/YTsJJ7QefYyADSDqE2v4mOLqEAQ6gqYMa54TzlSBSP4PqwXlgP/0csJ2EB46LbeXfqUBD7mPhYeGQMTiEKaJswgM3vASCvPkxqDEYTfJdwkZVE9geA1QdnFAnUqXgpwoFPP3196TySAUeGPDoT1cVsk8VljoUADlAR35X+beFhwVAoQEgDBBBn2cTHhg40jyRAwaqAEDxG0B4imOwys8x4HUYYH+oEdm/9ClgH2lGqHAqCDwAB3wHXVng+PguUlGY/DIFgYTfvlZ5WPAUGngyDQ8MKgxWDHoMPNVngcEuZb7f4JBPeunsxKBV95HAkH4QHAPmi+wPf+t+EAkP9KM23UGr+jzQP/rR/TA4DpygeB9Qkf4O/ATUTOZNOiFi4WHhUWLhoQ5ODGrV2amDwDSoTGaCCgg4X1W/iDRh8OTXwaL7POCwRP+y6c5LqXrkd8D56+YK+oQDVfd1yPMIcq6mAyIWHhYeJRYeUnn4hSQh9YP8BHIAq09waYpgICMKgj7kQIRCkepGgsXPP5GMzwMwgrIAcPRBj/dwTPUcJbh05246gKH2YeFh4VFi4REEBgwC6QA1wUVGV3SzQ0ZXoBgQDVF9HOgT72EfhERVsBQ02oL+TKFmv++A89CjNRYedrAX2mAviHPUtG+mfR5h8JDREJN/QKoOPS8EAxA5FxiYpsEMNYL3sY0OFuybqsMUMAOM0FSVIUO0aoIalIr0e6QbGFZ5WAAVCQAVNjxUnwEGO57u8BNA7mPwqfkZ6qCRfgZTzkWjjk4ymQksKjxMSWKqAjLlechoj+rLAEhkQpz0n+BvnJvJR5JOmFizxYKk0ECSSXhg4AMGUQYLBjzUh3yy4/egVHA4W+Fr8AvzSpPFFO0ISk9X4QHfigleeA/HVcO1+B3vS4jg3DINDlxXCw8LjxIJjyjQsNsUrH6IhYeFh4VHMZ3VWtjws/Cw8LDwsPCIZN7psLLwsPCw8LDwsPBIdyjR9pfZsgG6w7Rq9dI8Zb48Z11WoXtGVbOtiFyD63qcweUJKnGY2VvLxCoPqzyKhPLowNPE/z67Oj25oBo9s6wKPbO4nG1F5RosqUhPLzmVnphfje4fj8JGTqEhCw8Lj0KHR5eBXKdiwRn09MJS3HJsK8LX4JnFZenxudWpxc9OtvCwpklmTZOwKfkXXF2Jnlp0ugVGEQZGItBL0WMMkDNqVrCVxCxACgcgHS+qR4/Pq87gOMnCo1jBI4eeWVKeOg843cIjKjx2b5xO6/NnetqOdTNCF7+J2v+Jtt3lt9Zl1QFzxZoqxe8aoLhSDZr51jO2hunmNTNoxfLZNG7GAuoxaik9NngFXf9Svqd16bOKbuuT52m38t/6dg8NXEHdhi8TfS1eMkfA5kQDQ5Tve9OLdfgJVsnCo5jC88n51WnSxL+eWPD4ZMc7AhQDxi+mu19ZKQY/IHArw0GAoIcXGjockvm7iwsb7HNH31XUe8wSAZRD26ae8EDptbiliKhk+qn7h0dz6Kc3xluXAV6lc/9Y57O/TQ1/v3PvHEJ/6jnj72ue876Hv9Vjqr+jj6Dv/NCEHPrtX539f9Elh+4ekbi97P/6FxM/w363D8m8mnt+WW1au+65kg8PPP1HvrOQ7n91JXV+GbBwQaGpCz8wXPdSosKIbdtD/yxgWz4egHIznwPANXTiIgGyKE/qkrbNwNXtswKPem14vZSGzmBseL4znwODUg5gDGa8B4iog9r0PvpAf+p2+LvNJd730D/eR8sty1XWz4r/rcNHP2al03LojDpOn7XOcc7tkru8/eMzvI9tdejh/aBjpAvWPVbVox07epZMeMBHgcF5a2/H1Lg+YZBLheEM9lvc7Tr1zKebXs4Tpots6AdmiKkBSuq2OB76wzEBiiA1AygBZDgelBDMp5IGCb/vk014qIO76Y+cwZxJeKgDFMcCdMIG7ROzHRi0uDiH8LvcHioEQFDVhAQLtu/4J2/fFh4p5l/AHJg6Z654qsNH4SiGRGXgqI88MWi7vr5cQGH+Isc/8emud9IygNEXTBTpR4HZgnOC6vAzjXBO2G7MtAW0f8u0tJxHUYVRYcIDT/aiBo8/PWlWQDhPKCcVgPgdqgbmC5SNChYLjyThgYGGJzee4gCDyfyAEujUK1/AAoApjEgJ1NCs+XOFUxbgAiw69Uw0c3CeUELwkRTGeWYDONmEhzRbMOjKV8oh1e+QCbMlFeUBdQIlYVIo513uAER+JuGBv2GKQU3Jzyw8IsID0MAAw0AzAQNmQxf+DGApilEPgGHElIVCbQjTyuCDAWAQvSlpEMkmPDAo8aQGODAIiyI8AAjVnFIhArCon6nwgOqA+pBOWwuPEHjgCY4BlQiNPH4vX5gG+BxmQ7JmyN4dfWjzlq40b901oo3O60DDVrUIbNhGbr9hy99p5/YeSZsc+StnChBCOZlgCIjAt1JS/CLZhIcq+eFDwGCTfgVEXzDg9KhGQRymqSgPOEX9lAf8IFAYJuWB9+D3gCmG72Th4QMPgGDY5EV0Gz+p9ac0oHE7vz959rzIoVCAYuG6G+jNvHOp/7Ja4p/zDOLuizT5iL+Dmk+svvfSagI6AEtUoOA7wv8CNWKKCgEi/cctJoScs2FeZOoYhQUPQAIDTKoPRCvwN6CiP+3xvuq8jBptSQUeUBA4nh72xfkBdmrERVUeOJZ0tmIbCw8DPPBkvqs/fBpeeY+nNAYaoBGmMg7vHExL198s1IQOimcYDvF2Ev9ekBbvSwcRQAVgHXhvQOjgRxgX4WXdpLmxVx7d2W8VzVkwN7SPTA3+gvZbGPDAAMXAw2BUQ5wIieLJjYGLgQjnJcwE9WmP+8UPHtgOMJJNDftGjbagfygMmFbyPKCKYGZBkagQ0+GBfXHO2M7CQ4EHIigwQfQBBKUBaOApHXQjH9s9mlZsvIOGrGjiPFlcVeGAIhEQz/J7zy4uYPMFj/t0c88Bagcw+2jn8MDvAHDCZHGugYSn43DF+zDjCjqYs71/NuGhVsHCYNaTqzDYZQ6I3BZOSD2Hwg8eepUtNTSbDDwAiB9f68BN9onzQuKYqmZM8MDn8JtYeLjwWLJ0jjBFVBMFT11pngTd8PA9jMv/cfyix5RFHBhRQfHc4lIUa0v4dzTlvUiwSQCKo06kOQTzJn/TvfTZnrd9QQCI4Lvr6gtmHCJI2QZAQY6XLXioIVkdBqbIhp6BGpafkanP9aS1TB0n1X6LbJIYzI+Bby9OAAeetL1GLwn0aWAAvra8fsws0RWG30AHEI589W5aG/r0O55X9Xj9LFAjfhDx8/vg2jzLCq24+EKyDY9UB4ndzxw+LpLwQPj10UErRB6EVBzwa8D2D4o0ABqxf7RmkpgGMAb2h1+uymrzg4kHJIppBUcrzC7TEx6mCkwWdXLejZwfcg8nyBWHiIyFR3jWaVEGV5GDB8yUO9gRGDdTnNTyEZwC7ieREVY1+TFMJsnhL1dQUWoJUFNNGyWCA5+N3/eHuaKbdvAFTZw5r0ibMRYeFh5pK2M3c17iIEB0xS+5C05GRC4ADoRX5dNbh8YHXyyl4tA8IPH4R5ybDA5fhJZNEEEC2X0DVhL8QRK8UCQw/Qril8jkvhYeFh5pgccbnGWp5m7ATIEkN01dhy8AoU4h6RbIkChHR7QoycEvFlFxbGaIODcaIDlr3ZVGUwa+EPiDVDMGpl9PToUPC2FnEhKRJ8YtyuX6HpWp68IzRbFd24rONXh66Sn8v6nA92C8zmyRMFtwc6s3PCDiZ6YgN0KGW1VHqDrgDn6+gEpCU79T3CfiApMh4pd0htyPO/vFFcjNDOKnhi0rcgCJKw8uQ7j4VHpkenW69L5TeJ3YCqLMv21F5xpcdENl6tz3TFG79JnF5cUYLFR44GmI6ABubim1b++bRzBfTE+rNZsfUsDhhFvVAXbg87lUEpsvRPgfCAVmulYw9e5mk0/mhMCcgRO6KEViHHiUpyfmVqNf3V41pYWHCnvJxRPt+FiM+443qtGzC6sUHjwADjwNER2Q4MDTcuXyWQmDAWbKlNWXOLLdjaKoJsr+z2bTidBiEIn5Q5zwLvw+piQz+EEcgMSv8cNcIrGoAETAY1k1+t0D0VazP9EGalH9vrllT6KH3zmLuq2sUzjFgGCqxBVHnrjJTY5RpJOLuSYqOJTsz/c/m0EnUktUIXEzxuRMRdj7XrfUooTIE0OXFwkTpu/yNvTojOqcTXmSVR3FbMlLmJTPrqqVfXggAqDmcCCsaJpujsEgIilKzoYcPPs+nUYncpPXIeYLcQGLsLVuxsDpDMUh4QET5kmuaVIYTlL1mC/Ma0ZYyrCoPmHtecVT5PVrAeA/tawmbdrcPXtlCEdPne9xjiI/wQQOOANVcKhmyp5Pp5BtU+L+HtWMYYggWU4HAxTI/RzKlQCB6oP6K0yAPDbpbGp/xckWHsVMdUiQ3Dn8LJoyPfFek/dUWsKwsjM4QtVwLHI4TD4OmfRl8m/s/nQS2Ra/BkY/CAME6e0mgKgJeF16r6KhkxYVGkB+9PN61mQppuAAQBo0r5yddVuQLg3zRD75AJEFhtmwIqKimCqqjb/r+NtkW+I1MPpB+BoitV0HiO5ERYi8sKb1tzqnvlUdxRgeWVnoGt59zLdQMx9NM0CF4jA4RjF7defxcbYFXANcI68fxHEym9Laofag+lSQF8aUfgsPf59CcfC3ZAUeyOWQIVk465AFqT8RdeeoHAgYFO8dG2NbhGvgBxCoOf16Y96LmpiH1PZsZ6FaeFh4BPpFotykMhwb83G4oVgMhh3HRtmWxDXwA4gpChMF6pl0qFp4WHj4wsPk59DlMZKbTMlfGATbj46wLYVrEAOIlkym54GYzMls+j8sPCw8jPCABH7gNa+fw3RjonqWAw/vxLatR98g21K/BnoUBtcYBZL02iA64BE6z9Y6uhYeFh5GeLw1bb5YAgFOOT8/B6IBEhzqPBUUzNnyyVDbCngN9EQyXGsUftZNEc//iheg6j4yO/kfFh4WHgnwQEKSms9hepolJIFJPweDY/Mng2xLwzVQq5Y5mahOBEZPItNVIoovoVZqJv0d6NvCw8IjAR6odB4vH5gvlkRQb0RIZ6d4j2uuKODY9PGrZFv6roEJILj2+rIPmFekVqdHKDfT0RcLDwsPDzywzoi6UBHqjupPMFnRXPVziGrk3DZ+/IptabwG8roKE8Z1oD7lViXTCyzDXLnRXTcXxZiCSj+mQ5UUSXicWYFyzq4Sb5XLZD6RrUJp53gXVaecX9VObBfXdD4vYgllac3zwJPKqaXp1OdAHoFeiDch9VxRHRs+6ku2pf8aGM0XVn16LRA4SlXw4/dMOk8LHR4ABQZml2aU+3IHyunZkaoPakfnvNmGmgxvSw2HtaVK/c4X7+c80ppy7jjHGeCnlkt9IAMUHatRzmV1RH8V+55PZft0oBp83OYj21DrUa2p1cjW1OLN1tR8RBtqNrwNNRrWhk4fcJ5zHve0oJzf1yvYOaQJQmmFB8yTICcpnnTGDFJXdaz/qDfZlv5r4FEfDGvV/6HXAVH/h51YhQyekLm5L4UGjyanCGBUeaU9tR3diq6a1ZS6rq1HvbfVpiG7a9LYA2fR63tqUN/ttajbhrr0t3fr03Vzm9Il7zSnRgyVCjzgcx5o5YAEMAgbjNjmvDMpp3MTKsegaDO6NV30dgv6Ex/3loWN6K6lDem2xQ3594bUeX4jumFeI7p2TmP686wm9KeZTeiK6U3p8qnN6LIpzajD2BbU+I02VL4PnwP3l1O3Uvjxw84vxc/TBo9E1ZH41FKjK3KWrLyx133Uk2zL3DUwmS+m6EuU/2M6TJZCcZiy0qjISuK0V9tTx3Et6dp5Tei2JY3owVUN6CmGR88tdWjgzpo0an91Gr6vBg3YUYu6b6pDj6+uT/evOJtuWdSIrp7TlC6bdg61G9OKzhx4HpWBYgFETAOwzeligJfvC2C0oqtnN6HH361Hf8uvTw/zMR9Y2YDuW3423b30bLpjSUPqsqgh3bSgEXVieFw3tzFdw9tfxfD4I8Pj99Oa0m/faUaXTm5Gv5x4Dl084RxWJq2pcr/2lPO3NtEgliIk/OCYNnhgyUd1ZfcB471Vu+Gg83OSdmPlsfZID9syeA1wjf2iL1hZTwVCttRHVpUHqw089ZuxSfKTCS3oNwyAPzMIAASA4Yk19ehFBsWA92rRiH3VBUAAEgAFqgSAuW1xI/oLA+fy6c3o4kktqP1bragxmxWV+rMKgFkDRQOVwabQaWxmtGETpNP8xvTChjr03Lq69NSauvTE6nr0d4bHI3n16UGGB459z7Kz6U6Gx20Mj5tZidzI6uN6hsdfWH1czerjyhlN6Q8Mj9+56uPXk5rRzxkeP327Of14XHNq+HpbKtubIZJlFZI2eMAxqk5802t0xBaWVuqOyqdht6WlaM2RF2zL4DXANTaZL5jBjOQxFR7ZUh9Zg0fNikIh1H/9XGrFA/pHb7dkE+QcunJ2U+q8sDHdw09/mCYwUfqxqTKMTRaYLjBhem+tTc+ur8uDvQHdyebFDQyDK2by039Kc6FeWrCfot7Qc9l34fhMqvRvT7/izwCcl7fUppc21abuG+tQN+7jmbV1qSvD4zE+1qMMj4cYSH9d0YDu5ePDdLldmi6sPnTT5deTz6EL32pBHce2pPZjWlI7VjJtR7aiH/N7eL/WoHOpVK8AFZRm1QE1khZ46BEWhGrVmxFp0brqEOu88tMQN/XqD7vZloVrEAOIOwNX9X0UhvrIGjzYyQj/Rl0e5HBKduBB/4vJzekPDIHrGQaAwsMMh2d4gAMWgxkaYxgew/bWEDB5fmNd+jsPeEAGsPkTQwfwAYTOGtiOSr/sOFrxWX/eHuZOv221RF89N9emFxkez6+vQ88K9VGPnmDTRagPg+lyK6sPmCwXjm9Bjd5oS9Vea+c4Sh9r6zhs9db9fDqZTbF6Q9rSyeyLqf4q+2LgBM4ALPQ+0wKPh5QSd4iwRFUdkNK4od/98BnbsnANcK1N5gtCt2HqowuHbtOd95E1eDzdjiqzIqg1uB015QjGeWNb0c/Y7Pgdmx9XMwigFJpzdAMKQgzS59pTM94O2148uYVwqD7ESuH+lY7f44oZzTg6wtv26MBKpo347A0GzVCGzqBdNelVNn0AkT5ba1EvVh89WH1I0+VpVh9PQn0wPIT6cE2XGxliP2EnKqIuOH7On892ojJRQrSsrHKubCCUR9V+bemc19mR2iLzRaULDA9MdOvCIT3HZMmjrlptTMyY1SMsqurotrQ05R9+yrYsXANc62TUxwheiAsRF/xvu/BDYfGSOWnNOs0aPHgwlundQagDPM3bjnFMF/g/crryYEXUAtEQDFSEYWXehcy9wEDmPqBc4Gw9hVXMzxkqAAMUysj3HQcrIjSDGR6vvVeTXmH10ZfVh5/pAscpTJaLJ7agM1/jMCygxQCIBAs/VdHuDDqJAXLXzJOpzkAO6QIqGVQgBYbHmGkLqJO7fILpBpu29nJ3Vbf4GivCXHFVx/N8Q+cdfsK2LFwDXOtk1If3wZBPujla0KhL1uBxTUPK6dWRqg5oTw3Y79Gc8yhi+RJhoVYMQERTOLR7FsPn+nmN2alak95kYIw+UJ3eOngWjWbnKpysMHPgJ4GjVZgurD48pgs7TmG6IMJyLgNMAAM5G+kc5I+2oebDmtAjcxmCcOLmlsoYQAoMD7W0YOeX8z3SFmnoeklBXXU8v6w0rTz0mG1ZuAa41mHqQ1+FzusIT2/SWNbgwaoCiqEShzVFMtgDLSmnsZaxiUxSmVkKWLAaqcBO0Jps6vyU1cFdHE4FFAAHOFRlRGYsw2MMQ0SoD2G61PA1XbqwyQNzSEADJkkYuFJRDeefKcyWF5eeRK2HNaIchItT6SfCPgWChz4fovcYb4UwlMCLTbdXMkmloxRPQtzQKw49alsWrgGutVQfxsgL+z6wuJZf2PZmfjjMmm9ezS8VFZI1ePAgRRYnErB+xmHaOkPbicxO4Yh0m3A6sllSn9uF41uKqMqz6+oIswPmB8wQmCMwS2CewEwBMGC2IDKD0O4Ifg9gGQLThUEjTRcojbM5W1U4OzPti2AAnj34HHpp2Ul032yuTI9ktgggSGWbAsEDuRyxOphsE+uLNolaHYbJb0I6u+B4gW/o5R88ZFsWrsHEHZcJWKvqwzNtn+HRe2k1Uue86CnrcI6nAgrTPlmDBwYPD6I/cL4EMjmRU4HcCuRYINcCOReIfiAH42nOxUBOBhycPTjvoxdHS+D4hAMUjtBBDAU4RuEgFaYLQ0OaLm+y6RJznPJ2CMkiNAzHak53bheelbGBHBv87LM5tX9b6snwAECaDG3m5J9kACAFgkd84aY8upW98eoNYnSUuolKgIdUHaO3XEjLPngg5Zb/4dO0+ehrxrbxk/4p96uf09qPevgeZ/WRbp7jBG0rzxVqqyDfW913/ccv+54bjievw4Qdlwh4mNRHLGzLsNdLFsIJLucrIREQJRfSAZCswoMnnCFpC1mcyOZEVicUAbI8ke2JrE84MbtyKPUZhkc3Dq0ixIpQK/wWCL3Cj+E1XWoIxQHlEXOcMlSe4v2Rgp7zUlzZCIXTjaMomZ5ox8qm5RtNqNfykwRAfjnWnWhXlOCBcKwsoHsdR1mGTvTOgcCkK6PqEI5Sx1xBe2F5Li09eF/K7dPvtlLQK+/wkyn3rZ7XJ9+u8T3MwS/neo6BhLf//d+/A8/r8NdL03JeGz7uE3qsfZ+/I441YbsLD6k+DFmnMDMx61mFg5o9DHjg72IHD5bz1TknA/NHoD6QlHUvq4+/svpAshbCplAKCKMinPoc53xAfSDJK2a6sPrwmC4MCtV06cNzYzqMYzNBh0bMPGL1gYltGRjIsT45MvSXKdWo94qTBECum8Ir8l3Hvo8MHDNl5YGlE+AgdWbPriIkiqk3FPIGopgsgMeSg/ek1FYdfjx04Bz4ck5Kfevn9PG3q31hYDrGzk/HBMIDH2479nqBzm3FoUfo+/98Fnic499tiR0D1xpmYqDpwsqj++KTPaYLlIY69UD3baUKkqwqDwweDsv+ZU4TQiJWzHRh9aGbLkjkQlTkeYYHUtahPvqw+hCmC6sPj+nCpkrPzXWEUzXnJSfLNLBBfWTS78HO2K6LylOflScJgDwwN3N+j5Th8djg+NqngIi68rqIsmjFftRUdGmyvLC8NHXnG3rxgTtTans+mxQ6QH/479cp9a2f08ffvhsAj1nGYwTtg87+87/vOET9ZMrnd/y7zYHfH2CBP0l+F1xrXPMopsv2bd08D4N4VC3RRC028OA8igvGtRCmC1LBY6YL51skmC6sPmC6ILX8JYaH6jgVpgs7RR/Jb0DnjmXzBCFR5InAp/G81xFrBAmiLRlQAggp/2xsPeq/6iTqy/AAQJ5cWN6J7mTgeCnBA5mGUnVAeegFf1DmzhRlcXI7XHPFNVlwQy/cf3tK7R///iQUHthg4yevpNS/el4ffZPve6z9X8w09r/4wD0Udo5f/Ws/D+57kj6/3Z9NDPzuMJvWHnnJ068DD019mEyXBTk0a92VHnhAbQTNXUoFIFlXHpxPgZCtNF2Qlh4zXdj3IUwX9n3opgsyRJEQBsdpDwbJDfObiHwRY/QEA7Uw1AfPGK4/qAX1XJFLr+SdFAPIw/OKmPJQQ7TIQEQmonrzTHj352Z4aFEW3Mjd+csu2N8l6bb+416RwIGNjv1jU9L96+f00Td5vsd7/4sZvv3nHe4aalp98NXCpM5vzZHuoX3u/uzthD5xraOYLqZ0dWSXIgkQAMGDw7TiX7IAyTo88PTl/Aqkqf+WZ6gKxymiLtJ0Yd+HiLqw41Q1XR5nP8iVM5tSS05Fj6WO+81gRa7Ic27hniCIpFMNcCi6AYPjaTY3X80vRQMYHhIgt0w/zcmgLSrKAws5yRXgUPdSD9Ei3KfWJ1UTw3STBTf0/PdvTrod+WZVZHjgKbzs4ANJH0M9r6Djvf/F9MC+tx8bHnqumz55NdL5QaX88z+fBvZ37B8bjX3hWvuaLobJcuoyDTBLVbWZjmzTQoEHBhF8Dsi54JRzJIEBJu15vsuFPGHuR5zjgUI9qOiFnA+Rvo6G1PGovgr0HaY+MH8lHSFUDs3WHtSKnlxciQa9W4oGclMB8seJNZws1qICjx6jltL1PRxnqe7vUEO0esEfNUQrVIerPOa/fyPf7NEbbPiwaIY+uvawzE/mGPq2R75ZGaA8pob2HbS/9H+sYP9E2Dke+8eGQHAALLg+pn7i8Mj19Xs4dU6dKuv6TFs1m/huXns4WaWhb19o8JADSZ3Dgpmoav1QOdclldAq0s3hGA0DyA2NCzaoWeXUYXD0WF6BhqwpRYNXl0oASLsRmcsyTcnnEU9ZTnSe4YaL7O/Ak5DbiyvL0Lx9N0Ruuz4dG/ok1zf4xw8fRe7fdC5Hvl7he0yEQsPOfwGrK5xD0OvL7/cRtvPrK+x7A6irP3zWuD+usYCHyXSJ6PdQ8z0wn6nYwyMDT+PYEz6K7wOJY6meA8Ou6evNaUB+GRq2thS9zvDQAdKL/+dCNWVofktK8JAT4aA8EHVRbyLf/A4ff8eLK8oIeMzdd23k9vW/PkgaHthhzZHnIx9DP58jXy8PgMeUSP2uOPRwqGJCzojpWrzLyXBhamvnp6N9zwPXGNc6qt/DlO+BXB7k9DizbFeRvnRosjApdOWR6sCNst8vanFWaUjkBb6RKFPu1eOhaDPnivxuQl16c30pGrGuFL3B8DAB5NopHP2BuRXlfFPYJml4IF0Zcxyk510vN+hZVkGZz6KnpEuTBTd0D76x5+y9JlLLZwdk0Ovf//uH78eImEQ9jr7dh18v8+137+eTI/e79ejgUPBt+Li3p7+F799C//z38cD9jn67LvAccI0lPITfIyTfw+Q0xbwW+b835fZYeCjrsMDceSFC2BYzfqMOXFYb9Qa3pmeWVKHRG0rTyPWlAwFSeyAnrKVzxq52nknDAyuJycWBTF73ISuaeJLDwpyleCLixu6xqgzN2ntVaPvgqwW+g+jrfx2kHcdH+n6OJ/fC/beGHsN0HsgI9Xvt+XxiUn0G9YVjAIBLDt4d6/OTb9cGguO7fx9jH0dn33PAtRXwkKaLX76H5jTttqiMR1WG/e8tPLRFnJBN2jMkcexpVh9h8HDVxh8m1qVR68vQ2I2laQzDIwggnafzrF3UIQnruwCfJw2PsMzSaMlhcWdpHB5laeaeKwPbbFYnQdJ9x/ERHJ68JXSbsOOYPj/89RJ/eHw2IfTc1T7xPb794UggEL74fg9hO3ynoBeuxyqezh/0nXqsKmuAh7/TVC1PCAe4hEKY6rTw0OCBqf0vhsDjeXas+q0DA18Fq436rDa6L69Cb28uTeM3laZxDI8ggPRZ5dbyyMSUfwU2ScNj3IwFMZNFt3uxBois32GMtCjzWWJmS0x5lKWX8srSjD1/8G1Bkv9///cD2/vXiX2DIhtf/etA4DH8jn/468W+Yxg5FUHnbfoM6e8456AXQq5h22w/Pjzw2LimgfAQ81y04sgy4mKYJCfNVZO/y8JDg0eUqItfujqHhWsNbENXTqrLwChDk7aUpokMjygAOf9NjuJksI6HVDNJw0Odhq/PsPQL0xozSxFlcSMtkNQv8dMRN/r0PZf7ti++3+071o58syK2X96HTwQOSswJCTqO6bNDgfAYn3R/OMYmnvVbkBfm24R9D1xTXFtptuCah0ZcAhbFjs+kTnSWW3gY1p5FRCUsZPvbunHzAiYKVy3rOLopvb6uIk3dlktTtpamydyiAKTztMw6SVUzKGl4qGnKcJ6pyxFiPkRsMpy7xEK85KCWlq7Cg21yCY+efLNP2/2bhLb04L2B42zV4cc8+3zzw2Hf7Q9xRqfpGEHvYR+/167P3kq6P3msoH6DvvB3/z7KZs3VgcfFtYzBg6+xE3GJCo+TRMgdUw1UKKi5HgWt7VGioy1S3kdJGEPBHpgYvARl7UFt6Lllp9GMHbk0ndu07bmRAXLbTAZHhv0caYMHpKt6Y3ngoUZaTHNaBDycGxoOPRUevfLL0dTdl3ravi+m+o4lgELfftuxob7b/5fNhRl7fp+wj96H+ncwPMYm1ZfaL84jCHSmL4HzX37ogdBjmuHhE6415XoY1rNV4YHfk1Ub6vYnBDwu4TVpUQwoSH2wX6QOQ+OWmbVpzs5cms1t1nu5DkAYHlEAcses7IIjpXVb1Nm0OjzWbH4oqQSxIHi8s+tXJNv03b+lf//3W18Y7GC7X90ev8/Zdw1hkPm9NvFkOX2foL+DojyYfp9MX/q2C9nJG3Su+nfYemxw6PEA4ALDg5UHlghVB7z6/7fwMJgpevQCKe1B2aZc++PySQ1pwtYKNH93Ls3blZs0QBCFyabiSNnnod48sH/VGyup2bR+yoNvetz4vd4tR5N3/UK0NR+9EKgiZu27Orat3Ac/g0KiyOZUtw37/eBX833P4T1OzgrbP+zzqBP9MEEvrC9cOwEPbolmSxLKg2fXovq9HzwKmmV6QigPP6cpq5G2I5rTsPWVafHeXFq0h2eXMzySAciwteWp8RAu5pylRZ4KvOhT0JPHFx4h2aUes0WBx8vvlqdJO39GQXUrMGEN25gaSvUFvRYeuNV3X72/g1/NC4DHyMj9+J3rO7t+HTp9HyeA/A+/PvA+rllK8HAjLmpNU/g8MEPaBI8bxDo9BUtRPyHgAV+GGq7l36u/di6b62fQ8vdzadk+rqTH8EgWIF0Xs6JBHZEsr09bIJ9HSvBIxuehwWPEljbBkZPDj9DEnRf5tm9+8E9lR3JX0L7qZwe/mutvNn36ZuR+/I6Hc4nywveZwqrK1E/v1eVTh4dPSUK9mjr+/zJF3cIjgtkCMwYRF64yVoOh0WVWXVq6ryyt2p9LKxkeqQDkT5N5lixHZDKydEMSSWNJR1uCfB5pMVs4QiDNFjxF13zU23dMoVLWhPcuDGzwbfi94EeZzE/rsD7w+YEvZwf6XKL04bcNIkXJvHAuel+911SgBHgYoy1JmC3W51HwDE2YLVxd7Nfjm9Cc3RVp9cFcyj+QS3nckgXImE0VeDJc4ZkpBTZb1On4mYy2QHr3W1OVpfxR/5Dr1wtpyQd3B7b8D58KHJdrPnqe3n6vY2gLgsf242+E7u93jJl7/0g//PebZNghtlXPuw/AsaZ8VuCBqfgyUcw6TAOUB8wVnpTWdFhrGrmpKq07VIbWflCG1nBLBSAPzedoSiGbKQWGh5rnodfySFeeh1Qe03b/OelBlewOqI8xfkf70Lb/y1m+XW8/Pix0f9MxJr73I/r0n9uSPWWxPaIzs9lR3HdtBUoOHsnleWDhLr88j4LW9CixPg9OS6858FzqurQGbfqwDG08XIY2cEsFIIjCtB/JxYWQv5HhdPNk58Ekbbao8NAzTPfu6EPPuIWP1fT0ZDJMEV6U8Djwpf8kuJRGnM9Os/b9icbtaBfY9n850/eQ246/Hrq/qf9dn40r0Nf4nOe/DFh/qgMP+Dt0n4dqtrgT46IniTkFgfQkMazPI9dw0csxJJvzUeLgAeclJ3zdOLMB5X9QnrYeKUNbuKUKkL8tctVGOiqOJeHLiAqRpOGhzm2Jmp4eW14ywtwWwAMhxoFc+j5bL2SIvrWjTWDb/+WMAHgMCd1f73/FoQdDv96240NCSw7u4ByTBHi4YVqZ55Hy3BZDNTE7t8VgqmACG2eHthnRkqbuPIXe+7gs7fioDG3jlgpApu6oQB1GFU21UaBoi1q/FIs+bV4zwyNrCzKrFjkJEh55h/1zO0JHXZIboHTf+B3n0djtLX0b6pT6vZC0FbSv/tm0PZeE+jlwPOy38MAtod9m3v47FOURz/GIBA+uZq9PjJOzaqEioSalovDUMeUylPB/Jas2SlyGKauC2myiPLKoDu05WpZ2f1KWdnJLFSB3zuEV3uDbKKJqo0DwwOJOKASDJ5Bp4eP+y2olVfxYreeBFHUJjyBHaehoSmGDlYcfpTHbm/u297+YFgCPQYH7qv2O23EufcoLMQW9MPMX28n9th4bFLj9f/73PSGk7ZgtGjwwKa4A9TzUdWvxoJCrBF7fI49GvuOtmp8sSIq12QL/A/shfvZWc1pzqCK9f6ws7WV4pAqQNzacQo1fZ2iwgilqvg0/MyZpsyVsmcnReR0iLbugVxJTE8Xe2ZV5R6k+GjGNf/S2pr7t/YC5NVuOvRa4r9rvzk9HBYLgv//3L54p+xtPfwM2VqIPAwowo8PP/7lbRKf8EsSSrSQGBYkq+CoQ1OUXsAwD/k4WGCVCeXBx5IZD21Cfd2vQB5+WpQPHy9J+bqkAZMauitQI0HiCK7VnsOpXVD9GMtslDQ8s+NSpZ7wMoV6CH4sFPc1pzZC+MltR+jwSFnzySVHf+5m/f+Fjrqo1adelTuPJc5N3X0ZT9jjtnb2/EW3+/k7Gtu7jFwMH7pTdF9OobY2Nbd8X7/juu+XYq777qf0t5bBy2Cvvw8difb26qRIBHK9sOJmGbT07MGyNfrcfHxOHRzI5HqbsUv4fvpl3rgcOUBpQHM4So3mEB8kJBQ/4NlhtXDOtMW06cjId/qwsHeKWKkCufqchlerVgRsvUZnBWqPJACGZbZOGB24WNdavh+sQ2kumerpa0wOOvVfX1eFlGL/3HWOz93UW4cl+XOsArf96bjy4MMAw0NAw6F7bVJlG8oBT25ht57AD0r8W6LZjQxL2kfvv+2KK7zltPjrAdz+5/+RdP2U/x9eB7MAxsD3OXYJDwgPfcTLDMuw1Z99twuFs8ndEirTwbGiAHw8AfV5L0JIbqUCkWJktXGej5fDW9MqaGvTxF+XoI24ffl4uJYAMXHsGndK/PZXr3YGq8M8y/FMs+5CBiEgm+0wJHmoJfj1RzC9cG4u4yOUmlXVb1FKEyw76Fzj+13+/olfWnSrg0VeFx3oHHhIgGHiv8gB8bbPTRmytH2twbvq9ABZ1W/X3vV9M9t1v09FXfPdDH6PYHDr23aYQP8f7NGTrmeJ8ce6q6sD36s/fEaBc/VGwegJ439jU2oGH8HckV4JQXbdFD9MGLblRouHBq8y1YHDkHTyFjn5Vjj75slxKAFnzQUVq92YLKtunA53x6nlUfVA78bMc/42V7DI50DPRd0rwUEvwm+QrVllPZsU4WUEdfo+vvvefi7Lh49dEWFLAw6M+vPBQ1QcG40C0LVVo0NYqNGHnhYGDeNHB22j4ljoJbe/n/otqbzraz7iP7AclA4Je//2/72ncrvMc2BlUh4SHVFuHA9aQwXE+Y/9H73eruPBwix+7yy4ks2KcWr/Uuz5xHuEBkgowipXPA07R6xpx3kYjOvhpBQENwCMVgDwwvw6V79uBTmdYNBzWlurzWre1h7QTAAFMIq9GV4TUSUrwUCMupgrqUZ2mcq1aWdfjra2/Chxko7d1EPCIASQJ9TFwSxwgR771X6ryA549+8aWWgktaOLaxqN9jfugn4UHbw2zNGjx4btiKilIdQAeUFxDN9UP9X9sOzZaKz+Y3JoturPUsz4xL/g0ZtqCkg0Pdl6e+2ZLzjc6S5gnMFNgriQLkEX7KtFZr7WjU145n5qNaEstR7ahpiPaUKM34gARZkshzo5NVZWkBI+wdUuXrr/Zs/wCHKe+5QjdJSdhurx33H9m6UffrBW5DJjD4ac+TL4Pab5I9QEFsvBQcO7EWzta07DN1T1tz+cTfCGw8ZM+Cdtj/7ffO5/9HF8FwmPX528JZaSbK6qvAyZLPzZZBDxc1TVxZ7j/Y/be24xV0z1rtvjMptWn4gMWcrEv0/rEqaiQIuvzYHBcMLoFLdlXRThE4RhNBSAv5/M6sbywU11e8/YCXgO3/VutqO3o1tRCA0hpOExTWdaykFVISvDAjRK3f/NJLwoU3e9ROrZe7Surgx2lC/ffL/IYAJCo6sPPfBm6tRp99x//CXdrjj5HQ3dUFe11t+350j+VfMPxnrHt5H5v7DiLjn63PhAcX/xrT8zPEWSuwNchVYecy4JrkP9h99D8j9c3tPQu9rTcqSWLBcd9q6Yb0tLV2dRQmzBjUgFGkTdbGBwdR7WgzUcqiBAsIinJAmTjhxXp4nHNRWQmt3dHumhCC9FMAGnAJgwW3E716V+Y+6UMD93voWeaBvk9TCHbRe8/7DsQ4ATst+YUUasiVH24zlM18iKjL6r/Y8tx/8Srb344REO3MzzQXIjsDoGH3E7ut/Wz4JXhTH6OhAiLcJKy6nDNM6k61Ilwof6P73ZTr1WVApeZxMJcUId+67V4/R0Fr5ouAVLklAeDowODY8PhCiJnA7kbyQKk/2pWGzJDlE2Rqq+0p19NaU4XTzIDpNYQzu/o3OTEgofq94Cc1W1gFJEJC9niCSj8HtyOfuufdbn5k2EifwHwSFV9CIC45gHMhAl7fhz41J598Coasv0Ubg5Edn8RoDyO9RTbYFvsM//Q9eF+jkNmP4c0V9QIi0l1yIlwg9bXC/V/bD06WlxjXGuhOsRaLdx8TBas+qcqhLD/daoKpEjBg9PBMTdlHYMDWaLIFk0GIHuOV6Sfu2ojliHK4dcmw9vSZVPP8QXIma/xinHFMEybUgFk4zwHw4LX8en58YQx+D0SQ7alqU/eGbTyg+edduh5XgEN7QVaxfNbMMcFoUfMtEXqdUx9SPMlIfKC3A9z9EX4P9wQLgCy6qPHaN3RlzxtPf+9/thLBHgM3sbwQHOBgPdNbcaB3zmg4W2xT97HjzvbaX3LYy3/8EGjg9QPHNLX0UdOv3fNNwFTviZjt/1EXCc0XDNx7fga4lrK6/rKu7UZHv4mS0x1cMV0+KxUIKhr9aTL34H+iww8GBy/HN+MZ7+WE/NSMD8lGYDM3sMlAbF8gj4fhZeb/Omk5nT5jGa+AKnSn9ezLQbzWEzmUcpmC/75qh2sz7DF5/DYqyHbmOPUp6YpFiPS57rIyXJqdTGpPlA9y+w8DQKIk0AWUyFuFEaGchHORRu81QGBqQmg+Hwmtud9ZT/4ib6dFj+u9HHopoquOFQnqWOuRJh+r89l4esK1RG2uDX+V/ifYeU/CQ+YLLfzEgvONPw8wpT8VJWGvl+RgAebFu1HNqf1h8uJmbDvfVwmKYD0zKtJOVibRV8ykh2g5TkEe/WcpvTHWU19ASL8HUWsTkdUP0qB4DF/0RwCNJAoZjJdULbf33QpJZx2jukSd5zGlmNQVpGTk+VUgAjfh+48Xes4FmOZp74KxJtE5kRinFCuDhEHJC5MTEDBe6I50PGDhoyoqFGVaOBwIizSSSy/t17oOGiZBVNuh9FkMaSkq4tbd+qZR4MnLCo58OABjkpfi9+vKKbOYwp9VIDsPVaeOs3gFe6RVo60dT3y8ft6Irpy7bwmvgBpw5GXHCyGXchRk1SPXyB46I40PVX9wHsDXHgopgs754LmugSpD6Rd+5ovMnnMhYcDEMfhqGafeh2pihKIJZMpEEFimZtcpoPB+Le7vao0VGiYwrGJporiIHVDsyZwJMyejWWUuklhDF9cS+PSknJtWoOjFGvvqApBTUlPx3yWIhNtwYBnxTCKSwSiWA+K9ngB4kytN5kwKw9w0R8Mer+sUO4biV83LmhMnbj5AQQh3MJaNiFVYBRoSr4uPfWbS4+6wPlWEPWB9Gp1qr7ZfHHDt67/Q6auIzdCzH0JBIhXhcQjMnGIxGHgKpMESKimiWOe+EFDVRtGcOhhWb3MoKFCuikVHQpOwEM4Sr2+Dj/VAZNFnYKPfJ5bOCwrCwAVtOxgkTJbeK2Tm2bWE+UBUSYwKkBm7TrF8W8EzYDlMoStWVXctqQR3byokS9Acrq2K5b5HSkv+qTfAKqsxU0G55q6zYYtf09OfShJY5hGri9FKdWHHn2R/g84FdW5LyYTRp1EJ3NB9Pkw0icSg0nMtJEmjvLTVS3qPvJ3mTHqBw2jj0Mqjhg4fPwchlXh5ILWcXCYfR0J4VmDo3TW/LmiZgv+rzfxz/Ez5qfNZClUhyn7J+oNbsPVy8uJuqJRATKdq4QJ/0aQj4JVBya7dVnciO5a1tAXIBe+zVXQ0VcxNVkKFG1RARGva5knnGt6AtFry+vTU6K2qTZNX6znovg+ZOhWXQQ7ACAy+uLnQI0pEDn7FmaMO4FOB4gOEXVinQkKQe85wHAmt+nQ8KgNZcJbLJdDySI1OUijmCue2bNRVAf/b3ourepRHXoioL6oeTqcpoXmMOW8il4c4UMlc1Q0jwIQlBcMBQdAwIqm47iW9OCqBnTfirN9AdJgGJssxXAyXFrNFtxEcKLBmYYn1C2982ny7HkpqQ8170P4PuRC2AIgivkiiyQr4Vs9/yNIgahmTDBEnMEfh4EDBRGtiQFCgiIOizBoxNWGM1NWB4cMyeq1SSU45Dq04eZKRNVhmH6v5nbg/6rXbSm28GBzAxW7sHYKlkCIApApO6pEAwcrmtM5b+PR/Abc6vsC5M8cgcl5jDNLi2FKetrhcWjb1FhpQoTz/NTH0yyNdfWRkPehqI8ozlOTAzXBhFGiMKofJAwiepaqhILfT7m9/lM6bBOgoYKDz1FEVTRTRS7k5AFHmLmihWaNqehKRqkentVVR7odpYWaYcpRkPvn1xSLLkUByKTtEcEB1cGruHWa35ieWlePHl9d3xcgiMLk8HkUZ5MlbWZLFPUBL75eHNk0YS4xdOuf++H1f3gzUGMAUabwJ5oxiREZU2TGDwpB76vA0KFhUhuecKyWyyHAodcmNURXpLnicZIGZJMir8NU9Ef3Y2VCdRSKz4Of9FgndvEeZ7nHMIDM3n2y4xyNkofR5nRC6LX7pjr03Ia6vgC5ZVHjEqE60gqPKOpj2KoW8ciL6/9Qs04T5rxI56nHfCkjoi9qpXURgTGYMHIGrppI1ldVITFfSBwiqk9EB4AOFr/P5fvoS4aLnahP3ERxkr/is2Rj4Vi58psbVYmqOLzRFR9zhX1MnoWs2VzRIywY1ICFjLCg2DVMmHSYKYUebWF/xPXT6on1YbFObBhAWg1np2aUqfJsCqE+R6/NdajXltqBAPkRHKUlQHWkFR5R1AfyPuSiUJGdpwUFiJJI5oGIkg8ifA5KWDc+4L0O1uRgEa/+JafT+zlFE/I40gQOaa6YQrNCdRjWZfFUSGdfB2ZPZwIchaI8OLrRL/9UsTJ9GEBun1032pwTVjNn88zY5zfWpQE7alHfbbWppw9A7l7Oq75h0lwx93WkLVSr3lhQHzK0B9/HXf1XEd5Tt1GzTiVAEDaM1/swR1/8HKgmBZIQxlXqgIiBKsO5SjlDObC9IFEUiZtw5gGL8T1XYWgqQ53cpvo21BmyplXfhHM0wccRLy0YUxw+fg4JDm9olmuUMjhQtEkHg1pqIZOqozDgUalfe5r+Xjlaujc3ECCD150WLYzqJpp1XVufXt9TgwbtqhkIkJYjGRxceb24+zoyAg9dfdzYK4/6j/PmfSAJCaFb05wXPfNU+j9kxbFEgCSaMIF+EAGReKq3ByJ+IJEKBTBwW8wEUd7zwEdRNfCzyKn0idBw8jdUaCT4NwKcowngkGFZ6eeQmaSauWKaw4L/HaJkiJZJk6Wgy0mGKZashmrZtGg4tDUt2pNLixkefgCZubOC4+cIUwfwg7CD9O/vco3a/dVpxL4agQC5bh77Onj7kgKOtJstuFm8E6nMJfpNM26l87TAAHGXq4xnomqOVHeweiCiOlVjqsQp+ac6Wf0A4TFHNF+GWoMj7teIQyMQHO4KelBXTjHjeOo5JhB60s+TAAdUh76ANRSiMwHOgcdt/PvujdMzZrJkXXmcXYVaD29OC3fnBgLkV+N5kLPzM3CQc0i2wett6W8ckgU43jp4ViBAHl/DkZUoQCpmCWMFmtvi92TRcwRMdnPcfPEP30oHajIKxN+MiQaRBLNGNXGS+F3N1UgGGglmijGqEgeHMbISoDj8zJVeo5cQlKKTq5NHIwq4GlyY6igseMxnePgBZMTGys4gDxrEPH2+FhcufnptPRq6uwYN31sjFCBiAlwYkIoZODKiPORNo3rscTNOnzs34Slmir54w7fIQHXnZbizb4NMGJlIFgOIqkIQkRH1QOIQiWWm+qgROeglUAQQFIDIv02gCASG6hB1Q7AqNLwzZDXFIeesSB+HYaq97iB1llNw/BwwGY/tHu35X+hOUviq0lFmMAwgWTVbWHm0YuUxb1cu+QHkorGcvNWCa3OYBjLMGK6k3oSLGL/A4dhX2Dk6cGfNUID8fjqbKlhCshjCIeycM6I8cNPs3zJNSN8gGYy6EcL/IVPXZfhWc6CmChBVhcR8ITpElOpk0v8gSx3qpo0HJqrvRPld1Ntww61qf846srLF8zZi0FB9G+qaK6qpkgCOOFj1yIp0kEpwiOgKt53be3jAgclv97yyMv5/4pXgkOcRNvDT8XlW4QHnJk9Em7StnBEgw9b7qA74NniiGzJHr5rdlLpvrMPRlDrUh6MqYQC5aSGbQFzHNGwQFtfPMwYP3FwTZ86LL4zMcvi+ASsTnmi+/o8oAImFcZ1EsoRCQmo+iKtCAiHigiRBkUhlksRPAQsPMNxFqHWl4UJDVRsm/0YsASymOOLgECUFUWDJrdQWA4dal9Qw8Q3/o2c5p+NGLiMJyMNsgfmSDjBE6SOr8MCTn/Mrbp9dk+bszE0AyFXvcF0OhkRsICO/gwd+xb7n03mcEfpgXgPqyqbKs+vrRgLII5yeLmqTmmp9lBAVklF4RL05fbNPgwCi1D+VjkMdIDEzJgwiHjXimjUxleBCQMIg6Ke6j1siUI2eyGQ2EXr1hYa/Y1TWIY1NsVeiKjFwGCIrMFfgY9IHdBS4R4FAqttkHR4wPdinMXZzhQSAVHuVp8dfzSqBc0FQi6MaK42fT2xBdy9tSA/wJLdH2Dn6GKecRwHI05yeLup9lGBwZNTnIW8oXRZjnsScBYn+j/haL5oDNVmAuNmoqgrxhYhJjbggEengMR+J9JUYwKJtI/eT6eR+wEhUGonQUEOxMXC4BYxjRYzDFAeDQ1+DBf8b+DnuyHJ0pdAzTPHEZ9/HeW+eQ1O3l40BpG9+VV4vtqNYkOnnXHP0z3Oa0E0LG9GtmFbP8LiPk7uiAuS2xVxdLGzavlUeU3KiPnH0GxUhQdMK674RGBNAZAlDV4HIKuwxFaKmtLvT+nWIeByrCkg8po2b+i4hEPVnTF0oCiMGDNenEVtL1vVryFocRmgojtHI4DCUFcT/LCrQo/5/U90u68pDDlo2T66Y3IBms/kCE+b6aXXFMpBXzGwm/Bp/mduEbuAJbpEAws5T6QO5HM5RdqqWdMWRsSQxvxvJK5Hz6G726MOpqm+Pp6RxAp0LEE8eiBqJMZkxbkHlmAoxQcQ1aRJAosHEAwNpdvj9dPMzRO1VgEJtbmkBsQC1Dg34bdzSgaZiPvHFmrwLNhl9HAwOZJCqlcEkOB4euCLmIM22n0P9fxcaPAARHuR/W1hNAOSS8Q05/Hou/Y4HfyoAeWpdXTEprqRGVfwcuhn3eag3S/eRS+lGt+4HHHT3spdfT1/H9vE1X7wmjCeRzC0k5InEqOFc15nqZKXGHapRQSIHvARA1J8eUCgKw1dluI5eIzQ48UtWPDeqDcW/oS/aZAIHwq9PDYOD1MnnQHvgtUQndqpKItn9ChUebmr5c8tOowtGNSUU57mU11eJAhAU+pE+kGvnckQF81VKYB5HWBQoq/Aw3bx4CkJG6zdeggmjVSFT58IIgBhUSKIpEy8w5IFITJHEiw6Jmbt601WE/Nu0rVAWrrpQFEbMNBEqw0dpaCaKE4bVIioqONw8DjnZbVz+jxMUh+68BjgQojVd+2QhkOr2hQoPqA8AhCMiDYe0EuvJ/pJXdosKEExya4G5Kkg5D0tlLyE+Dh0mWYWHSTbjJn5i6HJjUlL+pns9JoxnJm6CGeNNKBNZqT7+EHWOjIzQ+MPEAUq05tRcVc0RDyxiKkPJEI0VKfaureJZ2U2sc+NMHhTNrXoeVxtIAnNmyUK1mQZzz1FL6WZ3mQxc8/s5bG4yG1MFQSr7FTo8JEA4MlKZF1/6GS8JGQaQzlwNXUyrh9ooQZPcwlSG6fOswwM3GUwVmCxSOkNGPzrIrEBEAWV3YDirmsXroPqaMYoKMUJEMWliZo0SpfEFChRKSFP3jf2uKwwXGPHQa7z+hoRGqNqI5XA4maN+4VioPR0c8DeZHNapAKAg+xQJeEiAcAJZtYHnUcfxrRIA8hs2Z346sbmYei8qnqMexwmqNtJehjCVGwiTru7sF89ABUhgwpiehsiKRCaqx5GqmjGqClF9IUaIOOvjxkwaDSQemIhB7ySfRWpyexMsTMDQfBq6Q1TN3VDVhidr1E05B2T1/4M0E1XFcWe/PFqfn50M0rD7osjAAwApn+sUJEZt0Z4dBSSwSLX4He/BPIHSKOG5G8kokEJRHvKmwtPvDg0gfnIa8zHgBHTqoDqOVD8VEuQPiSkRxaTxgMQAExn+Tfqnu4i3R2G4/gzVERoIDaNT1DVTOKKC+UGHdw5OAAd8GVBzqnMUIfKVy2dlLYO0WMFD90tAWUSpIlZC/RlRIFKo8MDNBaWhzq2AAgFQ9MWj5I0oksn4aess5eBGY3xMGR0iumNVmgjCN6LBJEGdKCAADPya3M/z0+0/ZpLIRK+YIzTRGYpzlyUDxU/VKcrQAESnrb3c6BjFNVXDsbimUHlFwVQpMqHaE3jQRwFDlG0KHR7SiYqQofqURPYjckNMT6+YGROgQlR/iBcijmPVDyQeZRIL/cZNHSMc3FXZkDYeA5GSCeqBhg4MNUPUdYh6oKH4NmTBYtQd3bylq/HaLFk6x6jmMl2bI0xlmD4vUmaLhUnSE/iKBDxwY8E+f/L15VzGMJ6DgFR2TNzyCydChfRfVisxIhOgRBJBEg6TBMXgBwbT+yZYGIDhrzTiJgr8PrPWXWlUG7h+fd9a4inoE+RHSmWwp3sfCw/2tWQIWjfffDMtW8Zj55NPxM/q1avT999/T23bsv+Gj/n444/TyJEjC3T8IgMPeWNiAAAa8UiMk4/gZ8ZgWj+yUjGtX3WoCn+IDhHFsRqr2g4Ha8zJ6oREpTLxKBSZSxLwU9/P6ctt7nFi4VZD2NVrnjjQgHmG7/Vm3rmEAtKmAQxVAeWmghc1VFBGsDDzOMJgY+GROXhIKKmAmDdvHr399ttUvnx5GjJkSMmDB244TJxTa4EIPwibMaOmLvAtUrN3Rx/hUDVBRHesCpPGAJJgoCggUKFg+t0PFH7A8Jgm8ZwN+DWgNkyRFDkwUXfUG7XKo9sYvtmoBBYGh7DPLTyyCw+ApH///gIaUCAlTnnIGw5PU9T/UP0gnXmxZVS5CipWA4ggw1LmPjhOVSU6o6kR6V8IgkmCWpAQiPpTSeoyO0EdYMhEL0ADURTUOvEbgFBiKO+oFixGxXqApKiEYi08Mg+HMLNHBQR+r1q1Kj333HPCXCmx8JB+EBSnUc0YUZyXn6xd2T8SlCGJ8CXMme6LT3ZAwhEKHSQm00Z3VgqoyOYHC2UbfX9/56cTao4Bg88R5wrwBUEDZggq0uvXBBDBNSnKZooOE6s8Mg8X+DYkYNTfpd8jDD5Bnxc5n4fpaSXMGF65TFUheMrCtMEi26bJdbIfzCpFmjt8BlgJPp6t6lUksbwRg68kDAjGz91+ZL/xn4rCYGAgcjJkRRNRzVyvLapeCzhEYaKYrgPWV3lr2vwik78Rpjjk5xYemYdHQeAQtm+xgIcM55pUSCeepYvBA4iEzdWAcxURGll4WYAEOROxnBEzUBIBIFVD2M+42hGOTzc/o9uiMgIYmPxnSvBSBx+UBHw9XdgBqpsoUoEFwTPqQC6M7Sw8LDwiFwVKxw0KW/9ujr54B1I+ASKIMPQesyQUIvI8YB4g9ImBLACCAe7Oo0n0mahmj//vcj/9JxyfSOpCfoZeY8N0XQAEABFKA74eGX3Cz84czka2aLYKFafj/5bNPI8LLriALr744liT4cmwJ6n9PDmYFRvlod98kOkYWDeJgRUP7cp1Rx7ieTLzF81JygcAZysGNxQB/CVQKFAJKgjw94tLThYt5k9xw6nYDvvAb4E+ECXRq5WHDcTFS+ZQD8yA5e8FIHqhgUW0VtGwyYuysjRC2LkW9PN0Kg/AIuiFfIcHH3xQhCktJJKDRJEoBlTQm03fH5J+/Iz54imsKxEJkVt4EGIwpuspDdUAxYIW5KNI5rtCTQ0Yv5hBmEddkOPSQ1UaeUJ5AJQwX4qriZJp5REGDwmW1atXW4CkKTGt2CoPkzMRuSAwXdSntfidByP8A7fyZwDJrPlzI5s2yUAg6rYAABQGTCycsx45kecP9QEwAhrFKYoS9TpkSnkgBAnTBU9MQAXZlgcPHowJE+Q6WPVRcPVRIuCh3qwwVZBZCYg4Jo33KQ6QYFDicwxcDGAMZORGZGqVNCgLLME5dOIi4a/B8YXC8Jybc54wVbqwykAOByCXqXOKOsAzuV2m4CHzGFRAID0bpgteSNO25ouFh6+zFpEXmDQYrH5Pdjl4MZChTDr1zBemA+ADM2LcjAVich7AEqVNnTNX7ANIoA8oHRwDx4ev4jrNN6PCA8cHzEZMWZjxBaYzCYRk+s4mPAASKA75ksrEKpDUIVLilIfp5sVUdMDAidI4URln4Jqf/uqgxmpqGNhRmoyKxCHh07+rfqAwpPopLlmhycAhbNtswwMOU/mCKaODA+oEJs/mzZtj2+F3vNesGRcD0nwFABAmnaHpCVjqttdee21su8suu8zXZHrppZfEdpiDYoIazh+fQznhBVNs2rRpxnNT9//jH/8o5rTA36O+8HdB4HlCwEO9iaFIYNrAXIEywAD2MyFMZkWq7+EYUCBQNtLvUhSnyYcN+HR+nm14YDKYfMEXog+wL7/80jdggwGrAwemj9wHJpHfQMSAly8MYtN26EtCQd8GKeWAhN8L+wFQJrhJU820bxDwokDlhIOHfvNjAMPnAXOjG0//R4hXRmpUtREGDXVbqWzQF/pE3zhGUSvGk04QpNJXNuGBAagOJPwtB4gaqcFAxKBCbgjUxl133RUDBAagDhCoEj8goX+oGfXl52+BOpAv/K4OXhUcML2geOrXr0/YTvXj6PksOH/52rVrl5jTgn1kDox6DaLAQt/mhIeH300PhaL6OQCAoIZQsNw+LNM1lYFWEvfJFjwwSNSnPwajHAh44mNg4YWBbfKFYKDKQYqfAIIJPKYoDuCDl6pqTCpBQgjbqc5cAEG+TEoB5yYVi/q9cH74TL5MTuRUgKHuY+HBJQBK4sAsDt8p0/DAwMOgUQcuQKE+caEk5CsohKv6S/SBKMECH4Q+IOG/wAsmk4SUbpao5o8+0xXbSvj4RYhU9aNuY+FhB3eJhVum4IFBLJ/GqskA9YEBpQ7wqBEY1fzQn/B+fQBS8jxgKgA6JhDAiSpfukNVAifIuQlFIl+qY9fCw8LDwiNCRmRQhikiJn5RDqkMMPhUc8Qk6SUIMKDVz2HqmMwDqWqkM1X1QajnI5WDyelqgqAKRP131RFs4WHhYeGRJDygMGBmYCCFJYPJJzsGYdi2qnNSh4vsRwWLarLI7eV20jxRTRaYNnq/QaAwfWbhYYFRYoGh+mIyZbYk4xxUIxmmXA7VsSoHK5SMPsilSYJt0I9ussjtddNFVUwmZ60KJXWmsN/vqj/HKg8LkhILkqIADyRmyZcpCiIHvWqamHI11IEKH4Russh+dNNF5p6YnK3YR8IN5kuy0RELDwsPC48kzZZklIcKBZOikANWVSh+kJEZnPhpMllMpos0hQAxExxkqBeASzapy8LDwsPCI4PwwIBVQ52mcK0apoU/xU8BqNupURY/E0d1hgaZTDJd3pTlGqRGLDwsPCw8MgwPdcYtnvByPgtMCnU+CPJFggY5+lFzSvxS1tXELhxPj97oQEBkRgWNPD85FwbHNKkmCw8LDwuPDMMDgxWORpmQZYpiwAwJC+WiHzXpzBQ9kWBI1hwBtNQJe6Zz1NPTLTwsPCw8IsADAwW+DjR90lsyjkb4QOBbAEjQ8Huy/cmEsLBp/1AUUA964prf+SKsK/uGDwYmFL4vQGQCm1yjpaDXxO98bHq6hVOhwSmd0ZZkAGG3Tb2Gh53bYoFRaMDIVJ6HBUJ6gJDMdbTKw4Kk0EBilUf2B3wycAjb1sLDwsPCI4JPJWwgnYifW3hYeFh4WHgknb0KWFp4WHhYeFh4WHgUhwI49hzjxZesz8P6PArtyWUHYvGugmbhYeFh4WFNn5TuAQsPC4+UbhyrGoq3akjH/++Ky5wlIW0rntcADtNFk3qQ371gwWBVRcbugX7d7qAKFcpZeBRTgOaWLk1Ht79t4ZGOJ6ntIzk1tW7+AKpcqYKFRzGFR5Oza/mCA2MhY08dO9CSG2gl9Xpd84efUJnc0hYgxQwgYSaLhYc1WTL+8IDsrVHtNAuQYgQPqMVH7roqUHVYeFh4ZBweuMkObRhNl/+6I1WpXNEqkCIOkdOqVqYB3e8KBYeFh4VHVuAhTbJxg/9Ot15/KSGEa1vRugY/uaAlPX7/NbQnf3gkcFh4WHhkFR4l1a9zon4ve/NYgNh7wN4DKd0DKe10opLWfm8bQbL3QPwesPCwTx17D9h7IKV7IKWdLH3tE9jeA/YesPCwTx17D9h7IKV74P8BpqFCbkNvT0sAAAAASUVORK5CYII=" />' %>
        <%  if (_.escape(item.get('os').toLowerCase().indexOf('windows')) >= 0) { %>
        <% img = '<img  title="Windows" alt="WINDOWS" src="data:image/jpg;base64,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"/>' %>
        <% } %>
        <%  if (_.escape(item.get('os').toLowerCase().indexOf('redhat')) >= 0) { %>
        <% img = '<img  title="RHEL" alt="RHEL" src="data:image/png;base64,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"/>' %>
        <% } %>

        </td>
        <td><%= _.escape(item.get('hostname') || '') %></td>
        <td><%= item.get('installationip') || '' %></td>
        <td><%= _.escape(item.get('configuredip') || '') %></td>
        <td><%if (item.get('startdate')) { %><%= _date(app.parseDate(item.get('startdate'))).format('MMM D, YYYY h:mm A') %><% } else { %>NULL<% } %></td>
        <td><%if (item.get('update')) { %><%= _date(app.parseDate(item.get('update'))).format('MMM D, YYYY h:mm A') %><% } else { %>NULL<% } %></td>
        <td><p class="help-inline"><b>Start Duration: </b><%=  days + 'd ' + hours + 'h ' + minutes + 'm ' + seconds + 's' %></p>
        <p class="help-inline"><b>Last Update: </b><%= daysnow + 'd ' + hoursnow + 'h ' + minutesnow + 'm ' + secondsnow + 's' %></p>     
        </td>

        <td class="showprogress">

        <div class="progress  <%= activeClass %>">

        <div class="bar <%= barClass  %> " role="progressbar"   aria-valuenow="<%= _.escape(item.get('progress') || '') %>" aria-valuemin="0" aria-valuemax="100" style="width: <%= _.escape(item.get('progress') || '') %>%;">
        <span class="sr-only"  ><%= _.escape(item.get('progress') || '') %>% Complete</span>
        </div>
        </div>
        <strong>
        <%= item.get('status').replace('|','<br />') || '' %>
        </strong>
        </td>
        <td><%= _.escape(item.get('image') || '') %></td>
        <td><%= img %><%= _.escape(item.get('os') || '') %></td>
        <td><table>
        <tr><th>Firmware</th><th>Ram</th><th>Cpu</th><th>Disk(s)</th><th>Net</th><th>Model S/N</th></tr>
        <tr>
        <td><%= _.escape(item.get('firmware') || '') %></td>
        <td><%= _.escape(item.get('ram') || '') %> MB</td>
        <td><%= _.escape(item.get('cpu') || '') %></td>
        <td><%= item.get('diskscount' || '') %></td>
        <td><%= item.get('netintcount' || '') %> ports.</td>
        <td><%= _.escape(item.get('model') || '') %>
        <%= _.escape(item.get('serial') || '') %></td></tr>

        </table>
        </td>



        </tr>
        <% }); %>
        </tbody>
        </table>

        <%=  view.getPaginationHtml(page) %>
    </script>
    <!-- underscore template for the model -->
    <script type="text/template"  id="provisioningnotificationsModelTemplate">


        <div class="progress">

        <div class="bar" role="progressbar"   aria-valuenow="<%= _.escape(item.get('progress') || '') %>" aria-valuemin="0" aria-valuemax="100" style="width: <%= _.escape(item.get('progress') || '') %>%;">
        <span class="sr-only"  ><%= _.escape(item.get('progress') || '') %>% Complete</span>
        </div>
        </div>
        <%= item.get('status') || '' %>
        <!-- <form class="form-horizontal" onsubmit="return false;">
        <fieldset>
        <div id="notifidInputContainer" class="control-group">
        <label class="control-label" for="notifid">Notifid</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="notifid" placeholder="Notifid" value="<%= _.escape(item.get('notifid') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="hostnameInputContainer" class="control-group">
        <label class="control-label" for="hostname">Hostname</label>
        <div class="controls inline-inputs">-->
        <textarea class="input-xlarge hide" id="hostname" rows="3"><%= _.escape(item.get('hostname') || '') %></textarea>
        <!--<span class="help-inline"></span>
        </div>
        </div>
        <div id="installationipInputContainer" class="control-group">
        <label class="control-label" for="installationip">Installationip</label>
        <div class="controls inline-inputs">
        <textarea class="input-xlarge" id="installationip" rows="3"><%= _.escape(item.get('installationip') || '') %></textarea>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="configuredipInputContainer" class="control-group">
        <label class="control-label" for="configuredip">Configuredip</label>
        <div class="controls inline-inputs">-->
        <textarea class="input-xlarge hide" id="configuredip" rows="3"><%= _.escape(item.get('configuredip') || '') %></textarea>
        <!--<span class="help-inline"></span>
        </div>
        </div>
        <div id="startdateInputContainer" class="control-group">
        <label class="control-label" for="startdate">Startdate</label>
        <div class="controls inline-inputs">
        <div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
        <input id="startdate" type="text" value="<%= _date(app.parseDate(item.get('startdate'))).format('YYYY-MM-DD') %>" />
        <span class="add-on"><i class="icon-calendar"></i></span>
        </div>
        <div class="input-append bootstrap-timepicker-component">
        <input id="startdate-time" type="text" class="timepicker-default input-small" value="<%= _date(app.parseDate(item.get('startdate'))).format('h:mm A') %>" />
        <span class="add-on"><i class="icon-time"></i></span>
        </div>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="statusInputContainer" class="control-group">
        <label class="control-label" for="status">Status</label>
        <div class="controls inline-inputs">
        <textarea class="input-xlarge" id="status" rows="3"><%= _.escape(item.get('status') || '') %></textarea>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="progressInputContainer" class="control-group">
        <label class="control-label" for="progress">Progress</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="progress" placeholder="Progress" value="<%= _.escape(item.get('progress') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="imageInputContainer" class="control-group">
        <label class="control-label" for="image">Image</label>
        <div class="controls inline-inputs">
        <textarea class="input-xlarge" id="image" rows="3"><%= _.escape(item.get('image') || '') %></textarea>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="firmwareInputContainer" class="control-group">
        <label class="control-label" for="firmware">Firmware</label>
        <div class="controls inline-inputs">
        <textarea class="input-xlarge" id="firmware" rows="3"><%= _.escape(item.get('firmware') || '') %></textarea>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="ramInputContainer" class="control-group">
        <label class="control-label" for="ram">Ram</label>
        <div class="controls inline-inputs">
        <textarea class="input-xlarge" id="ram" rows="3"><%= _.escape(item.get('ram') || '') %></textarea>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="cpuInputContainer" class="control-group">
        <label class="control-label" for="cpu">Cpu</label>
        <div class="controls inline-inputs">
        <textarea class="input-xlarge" id="cpu" rows="3"><%= _.escape(item.get('cpu') || '') %></textarea>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="diskscountInputContainer" class="control-group">
        <label class="control-label" for="diskscount">Diskscount</label>
        <div class="controls inline-inputs">
        <textarea class="input-xlarge" id="diskscount" rows="3"><%= _.escape(item.get('diskscount') || '') %></textarea>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="netintcountInputContainer" class="control-group">
        <label class="control-label" for="netintcount">Netintcount</label>
        <div class="controls inline-inputs">
        <textarea class="input-xlarge" id="netintcount" rows="3"><%= _.escape(item.get('netintcount') || '') %></textarea>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="modelInputContainer" class="control-group">
        <label class="control-label" for="model">Model</label>
        <div class="controls inline-inputs">
        <textarea class="input-xlarge" id="model" rows="3"><%= _.escape(item.get('model') || '') %></textarea>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="serialInputContainer" class="control-group">
        <label class="control-label" for="serial">Serial</label>
        <div class="controls inline-inputs">
        <textarea class="input-xlarge" id="serial" rows="3"><%= _.escape(item.get('serial') || '') %></textarea>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="osInputContainer" class="control-group">
        <label class="control-label" for="os">Os</label>
        <div class="controls inline-inputs">
        <textarea class="input-xlarge" id="os" rows="3"><%= _.escape(item.get('os') || '') %></textarea>
        <span class="help-inline"></span>
        </div>
        </div>
        </fieldset>
        </form> -->
        <% img = '<img  class="AIX" title="AIX" alt="AIX" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQ8AAADGCAYAAADMvwX2AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAUPVJREFUeF7tnQe4VMXZx69wqQJiQ3qRKl1E0MQkpqlJTIzRGAuiYm+xJhZsKCJIE6WJIE2QJr33cq/0jlQBQVTArjHGlO/93v+cM7tzZueU3bu7tzD7PPPce3fPmXP23DO/83/LvHPSV3un5NiXvQL2CtgrkPQVADxss9fA3gP2Hkj2HrDgsPC094C9B1K6B1LaKVlC2e3tU83eAyXvHrDwsE8dew/YeyCleyClnexTpOQ9RbL1P100qQf163YHPX7/NbYVoWswZXhX2pM/nJK5Dyw87FMnK/cAbs7KlSrSKVVOpgoVyhF79m0rQtdA/F/Kl6Pz2zaJDJGs3DjJ0MxuW/JUTaerfyGgYYFRPIB5WtXKtGpGn1AVYuFhlUdG74GhL99PVS04ih04zzitCh3aMDoQIBm9cayKKHkqIpn/KWzos86sWuwGjlVIOVQmtzRd/uuOFh7J3PB22/QBb0T/h9jPUcHCowj5NpIBYy4DJGg8WOVhzZaM3QO3Xn9pxsBxcZdT6KZXz4q1v/Q4k+qfW954vOqNy9Klfz01ti1+17dtf0Ul8XntFonO3Av+XFl8hm30wdfsJxXEZ2fULSM+w7HU81J/V/dt+cuKCeeP98IGN87vgck1qWr13Ni2OL8rnjydsD9+oo+rnjuDKp1WOrS/oONVqVwx0PeRsRvHPsHT9wQvrtcSnvuwwZDq5xhAj82rTb979DQCOPD7s6vqEgazPki7LqkjBhy2w6C6d1wNsS0GndwWMMF7gJJ+TnL7W4eelfAZBiuOnVv2JPGZ7Af76BBR+5X74Xj4DvIYv7zL38y76IYq4hwfmVFLQErtD/0AKAAj+sZ3VQGTynWGk3v22G6+6sPCwyqPjN0Drc6pn1F4YHDKQYGnLCBx55vVY+9h8OA9DPrylUrF3sdAv6FPNTEQpdLAe4CAuj/6Rh/YTsJJ7QefYyADSDqE2v4mOLqEAQ6gqYMa54TzlSBSP4PqwXlgP/0csJ2EB46LbeXfqUBD7mPhYeGQMTiEKaJswgM3vASCvPkxqDEYTfJdwkZVE9geA1QdnFAnUqXgpwoFPP3196TySAUeGPDoT1cVsk8VljoUADlAR35X+beFhwVAoQEgDBBBn2cTHhg40jyRAwaqAEDxG0B4imOwys8x4HUYYH+oEdm/9ClgH2lGqHAqCDwAB3wHXVng+PguUlGY/DIFgYTfvlZ5WPAUGngyDQ8MKgxWDHoMPNVngcEuZb7f4JBPeunsxKBV95HAkH4QHAPmi+wPf+t+EAkP9KM23UGr+jzQP/rR/TA4DpygeB9Qkf4O/ATUTOZNOiFi4WHhUWLhoQ5ODGrV2amDwDSoTGaCCgg4X1W/iDRh8OTXwaL7POCwRP+y6c5LqXrkd8D56+YK+oQDVfd1yPMIcq6mAyIWHhYeJRYeUnn4hSQh9YP8BHIAq09waYpgICMKgj7kQIRCkepGgsXPP5GMzwMwgrIAcPRBj/dwTPUcJbh05246gKH2YeFh4VFi4REEBgwC6QA1wUVGV3SzQ0ZXoBgQDVF9HOgT72EfhERVsBQ02oL+TKFmv++A89CjNRYedrAX2mAviHPUtG+mfR5h8JDREJN/QKoOPS8EAxA5FxiYpsEMNYL3sY0OFuybqsMUMAOM0FSVIUO0aoIalIr0e6QbGFZ5WAAVCQAVNjxUnwEGO57u8BNA7mPwqfkZ6qCRfgZTzkWjjk4ymQksKjxMSWKqAjLlechoj+rLAEhkQpz0n+BvnJvJR5JOmFizxYKk0ECSSXhg4AMGUQYLBjzUh3yy4/egVHA4W+Fr8AvzSpPFFO0ISk9X4QHfigleeA/HVcO1+B3vS4jg3DINDlxXCw8LjxIJjyjQsNsUrH6IhYeFh4VHMZ3VWtjws/Cw8LDwsPCIZN7psLLwsPCw8LDwsPBIdyjR9pfZsgG6w7Rq9dI8Zb48Z11WoXtGVbOtiFyD63qcweUJKnGY2VvLxCoPqzyKhPLowNPE/z67Oj25oBo9s6wKPbO4nG1F5RosqUhPLzmVnphfje4fj8JGTqEhCw8Lj0KHR5eBXKdiwRn09MJS3HJsK8LX4JnFZenxudWpxc9OtvCwpklmTZOwKfkXXF2Jnlp0ugVGEQZGItBL0WMMkDNqVrCVxCxACgcgHS+qR4/Pq87gOMnCo1jBI4eeWVKeOg843cIjKjx2b5xO6/NnetqOdTNCF7+J2v+Jtt3lt9Zl1QFzxZoqxe8aoLhSDZr51jO2hunmNTNoxfLZNG7GAuoxaik9NngFXf9Svqd16bOKbuuT52m38t/6dg8NXEHdhi8TfS1eMkfA5kQDQ5Tve9OLdfgJVsnCo5jC88n51WnSxL+eWPD4ZMc7AhQDxi+mu19ZKQY/IHArw0GAoIcXGjockvm7iwsb7HNH31XUe8wSAZRD26ae8EDptbiliKhk+qn7h0dz6Kc3xluXAV6lc/9Y57O/TQ1/v3PvHEJ/6jnj72ue876Hv9Vjqr+jj6Dv/NCEHPrtX539f9Elh+4ekbi97P/6FxM/w363D8m8mnt+WW1au+65kg8PPP1HvrOQ7n91JXV+GbBwQaGpCz8wXPdSosKIbdtD/yxgWz4egHIznwPANXTiIgGyKE/qkrbNwNXtswKPem14vZSGzmBseL4znwODUg5gDGa8B4iog9r0PvpAf+p2+LvNJd730D/eR8sty1XWz4r/rcNHP2al03LojDpOn7XOcc7tkru8/eMzvI9tdejh/aBjpAvWPVbVox07epZMeMBHgcF5a2/H1Lg+YZBLheEM9lvc7Tr1zKebXs4Tpots6AdmiKkBSuq2OB76wzEBiiA1AygBZDgelBDMp5IGCb/vk014qIO76Y+cwZxJeKgDFMcCdMIG7ROzHRi0uDiH8LvcHioEQFDVhAQLtu/4J2/fFh4p5l/AHJg6Z654qsNH4SiGRGXgqI88MWi7vr5cQGH+Isc/8emud9IygNEXTBTpR4HZgnOC6vAzjXBO2G7MtAW0f8u0tJxHUYVRYcIDT/aiBo8/PWlWQDhPKCcVgPgdqgbmC5SNChYLjyThgYGGJzee4gCDyfyAEujUK1/AAoApjEgJ1NCs+XOFUxbgAiw69Uw0c3CeUELwkRTGeWYDONmEhzRbMOjKV8oh1e+QCbMlFeUBdQIlYVIo513uAER+JuGBv2GKQU3Jzyw8IsID0MAAw0AzAQNmQxf+DGApilEPgGHElIVCbQjTyuCDAWAQvSlpEMkmPDAo8aQGODAIiyI8AAjVnFIhArCon6nwgOqA+pBOWwuPEHjgCY4BlQiNPH4vX5gG+BxmQ7JmyN4dfWjzlq40b901oo3O60DDVrUIbNhGbr9hy99p5/YeSZsc+StnChBCOZlgCIjAt1JS/CLZhIcq+eFDwGCTfgVEXzDg9KhGQRymqSgPOEX9lAf8IFAYJuWB9+D3gCmG72Th4QMPgGDY5EV0Gz+p9ac0oHE7vz959rzIoVCAYuG6G+jNvHOp/7Ja4p/zDOLuizT5iL+Dmk+svvfSagI6AEtUoOA7wv8CNWKKCgEi/cctJoScs2FeZOoYhQUPQAIDTKoPRCvwN6CiP+3xvuq8jBptSQUeUBA4nh72xfkBdmrERVUeOJZ0tmIbCw8DPPBkvqs/fBpeeY+nNAYaoBGmMg7vHExL198s1IQOimcYDvF2Ev9ekBbvSwcRQAVgHXhvQOjgRxgX4WXdpLmxVx7d2W8VzVkwN7SPTA3+gvZbGPDAAMXAw2BUQ5wIieLJjYGLgQjnJcwE9WmP+8UPHtgOMJJNDftGjbagfygMmFbyPKCKYGZBkagQ0+GBfXHO2M7CQ4EHIigwQfQBBKUBaOApHXQjH9s9mlZsvIOGrGjiPFlcVeGAIhEQz/J7zy4uYPMFj/t0c88Bagcw+2jn8MDvAHDCZHGugYSn43DF+zDjCjqYs71/NuGhVsHCYNaTqzDYZQ6I3BZOSD2Hwg8eepUtNTSbDDwAiB9f68BN9onzQuKYqmZM8MDn8JtYeLjwWLJ0jjBFVBMFT11pngTd8PA9jMv/cfyix5RFHBhRQfHc4lIUa0v4dzTlvUiwSQCKo06kOQTzJn/TvfTZnrd9QQCI4Lvr6gtmHCJI2QZAQY6XLXioIVkdBqbIhp6BGpafkanP9aS1TB0n1X6LbJIYzI+Bby9OAAeetL1GLwn0aWAAvra8fsws0RWG30AHEI589W5aG/r0O55X9Xj9LFAjfhDx8/vg2jzLCq24+EKyDY9UB4ndzxw+LpLwQPj10UErRB6EVBzwa8D2D4o0ABqxf7RmkpgGMAb2h1+uymrzg4kHJIppBUcrzC7TEx6mCkwWdXLejZwfcg8nyBWHiIyFR3jWaVEGV5GDB8yUO9gRGDdTnNTyEZwC7ieREVY1+TFMJsnhL1dQUWoJUFNNGyWCA5+N3/eHuaKbdvAFTZw5r0ibMRYeFh5pK2M3c17iIEB0xS+5C05GRC4ADoRX5dNbh8YHXyyl4tA8IPH4R5ybDA5fhJZNEEEC2X0DVhL8QRK8UCQw/Qril8jkvhYeFh5pgccbnGWp5m7ATIEkN01dhy8AoU4h6RbIkChHR7QoycEvFlFxbGaIODcaIDlr3ZVGUwa+EPiDVDMGpl9PToUPC2FnEhKRJ8YtyuX6HpWp68IzRbFd24rONXh66Sn8v6nA92C8zmyRMFtwc6s3PCDiZ6YgN0KGW1VHqDrgDn6+gEpCU79T3CfiApMh4pd0htyPO/vFFcjNDOKnhi0rcgCJKw8uQ7j4VHpkenW69L5TeJ3YCqLMv21F5xpcdENl6tz3TFG79JnF5cUYLFR44GmI6ABubim1b++bRzBfTE+rNZsfUsDhhFvVAXbg87lUEpsvRPgfCAVmulYw9e5mk0/mhMCcgRO6KEViHHiUpyfmVqNf3V41pYWHCnvJxRPt+FiM+443qtGzC6sUHjwADjwNER2Q4MDTcuXyWQmDAWbKlNWXOLLdjaKoJsr+z2bTidBiEIn5Q5zwLvw+piQz+EEcgMSv8cNcIrGoAETAY1k1+t0D0VazP9EGalH9vrllT6KH3zmLuq2sUzjFgGCqxBVHnrjJTY5RpJOLuSYqOJTsz/c/m0EnUktUIXEzxuRMRdj7XrfUooTIE0OXFwkTpu/yNvTojOqcTXmSVR3FbMlLmJTPrqqVfXggAqDmcCCsaJpujsEgIilKzoYcPPs+nUYncpPXIeYLcQGLsLVuxsDpDMUh4QET5kmuaVIYTlL1mC/Ma0ZYyrCoPmHtecVT5PVrAeA/tawmbdrcPXtlCEdPne9xjiI/wQQOOANVcKhmyp5Pp5BtU+L+HtWMYYggWU4HAxTI/RzKlQCB6oP6K0yAPDbpbGp/xckWHsVMdUiQ3Dn8LJoyPfFek/dUWsKwsjM4QtVwLHI4TD4OmfRl8m/s/nQS2Ra/BkY/CAME6e0mgKgJeF16r6KhkxYVGkB+9PN61mQppuAAQBo0r5yddVuQLg3zRD75AJEFhtmwIqKimCqqjb/r+NtkW+I1MPpB+BoitV0HiO5ERYi8sKb1tzqnvlUdxRgeWVnoGt59zLdQMx9NM0CF4jA4RjF7defxcbYFXANcI68fxHEym9Laofag+lSQF8aUfgsPf59CcfC3ZAUeyOWQIVk465AFqT8RdeeoHAgYFO8dG2NbhGvgBxCoOf16Y96LmpiH1PZsZ6FaeFh4BPpFotykMhwb83G4oVgMhh3HRtmWxDXwA4gpChMF6pl0qFp4WHj4wsPk59DlMZKbTMlfGATbj46wLYVrEAOIlkym54GYzMls+j8sPCw8jPCABH7gNa+fw3RjonqWAw/vxLatR98g21K/BnoUBtcYBZL02iA64BE6z9Y6uhYeFh5GeLw1bb5YAgFOOT8/B6IBEhzqPBUUzNnyyVDbCngN9EQyXGsUftZNEc//iheg6j4yO/kfFh4WHgnwQEKSms9hepolJIFJPweDY/Mng2xLwzVQq5Y5mahOBEZPItNVIoovoVZqJv0d6NvCw8IjAR6odB4vH5gvlkRQb0RIZ6d4j2uuKODY9PGrZFv6roEJILj2+rIPmFekVqdHKDfT0RcLDwsPDzywzoi6UBHqjupPMFnRXPVziGrk3DZ+/IptabwG8roKE8Z1oD7lViXTCyzDXLnRXTcXxZiCSj+mQ5UUSXicWYFyzq4Sb5XLZD6RrUJp53gXVaecX9VObBfXdD4vYgllac3zwJPKqaXp1OdAHoFeiDch9VxRHRs+6ku2pf8aGM0XVn16LRA4SlXw4/dMOk8LHR4ABQZml2aU+3IHyunZkaoPakfnvNmGmgxvSw2HtaVK/c4X7+c80ppy7jjHGeCnlkt9IAMUHatRzmV1RH8V+55PZft0oBp83OYj21DrUa2p1cjW1OLN1tR8RBtqNrwNNRrWhk4fcJ5zHve0oJzf1yvYOaQJQmmFB8yTICcpnnTGDFJXdaz/qDfZlv5r4FEfDGvV/6HXAVH/h51YhQyekLm5L4UGjyanCGBUeaU9tR3diq6a1ZS6rq1HvbfVpiG7a9LYA2fR63tqUN/ttajbhrr0t3fr03Vzm9Il7zSnRgyVCjzgcx5o5YAEMAgbjNjmvDMpp3MTKsegaDO6NV30dgv6Ex/3loWN6K6lDem2xQ3594bUeX4jumFeI7p2TmP686wm9KeZTeiK6U3p8qnN6LIpzajD2BbU+I02VL4PnwP3l1O3Uvjxw84vxc/TBo9E1ZH41FKjK3KWrLyx133Uk2zL3DUwmS+m6EuU/2M6TJZCcZiy0qjISuK0V9tTx3Et6dp5Tei2JY3owVUN6CmGR88tdWjgzpo0an91Gr6vBg3YUYu6b6pDj6+uT/evOJtuWdSIrp7TlC6bdg61G9OKzhx4HpWBYgFETAOwzeligJfvC2C0oqtnN6HH361Hf8uvTw/zMR9Y2YDuW3423b30bLpjSUPqsqgh3bSgEXVieFw3tzFdw9tfxfD4I8Pj99Oa0m/faUaXTm5Gv5x4Dl084RxWJq2pcr/2lPO3NtEgliIk/OCYNnhgyUd1ZfcB471Vu+Gg83OSdmPlsfZID9syeA1wjf2iL1hZTwVCttRHVpUHqw089ZuxSfKTCS3oNwyAPzMIAASA4Yk19ehFBsWA92rRiH3VBUAAEgAFqgSAuW1xI/oLA+fy6c3o4kktqP1bragxmxWV+rMKgFkDRQOVwabQaWxmtGETpNP8xvTChjr03Lq69NSauvTE6nr0d4bHI3n16UGGB459z7Kz6U6Gx20Mj5tZidzI6uN6hsdfWH1czerjyhlN6Q8Mj9+56uPXk5rRzxkeP327Of14XHNq+HpbKtubIZJlFZI2eMAxqk5802t0xBaWVuqOyqdht6WlaM2RF2zL4DXANTaZL5jBjOQxFR7ZUh9Zg0fNikIh1H/9XGrFA/pHb7dkE+QcunJ2U+q8sDHdw09/mCYwUfqxqTKMTRaYLjBhem+tTc+ur8uDvQHdyebFDQyDK2by039Kc6FeWrCfot7Qc9l34fhMqvRvT7/izwCcl7fUppc21abuG+tQN+7jmbV1qSvD4zE+1qMMj4cYSH9d0YDu5ePDdLldmi6sPnTT5deTz6EL32pBHce2pPZjWlI7VjJtR7aiH/N7eL/WoHOpVK8AFZRm1QE1khZ46BEWhGrVmxFp0brqEOu88tMQN/XqD7vZloVrEAOIOwNX9X0UhvrIGjzYyQj/Rl0e5HBKduBB/4vJzekPDIHrGQaAwsMMh2d4gAMWgxkaYxgew/bWEDB5fmNd+jsPeEAGsPkTQwfwAYTOGtiOSr/sOFrxWX/eHuZOv221RF89N9emFxkez6+vQ88K9VGPnmDTRagPg+lyK6sPmCwXjm9Bjd5oS9Vea+c4Sh9r6zhs9db9fDqZTbF6Q9rSyeyLqf4q+2LgBM4ALPQ+0wKPh5QSd4iwRFUdkNK4od/98BnbsnANcK1N5gtCt2HqowuHbtOd95E1eDzdjiqzIqg1uB015QjGeWNb0c/Y7Pgdmx9XMwigFJpzdAMKQgzS59pTM94O2148uYVwqD7ESuH+lY7f44oZzTg6wtv26MBKpo347A0GzVCGzqBdNelVNn0AkT5ba1EvVh89WH1I0+VpVh9PQn0wPIT6cE2XGxliP2EnKqIuOH7On892ojJRQrSsrHKubCCUR9V+bemc19mR2iLzRaULDA9MdOvCIT3HZMmjrlptTMyY1SMsqurotrQ05R9+yrYsXANc62TUxwheiAsRF/xvu/BDYfGSOWnNOs0aPHgwlundQagDPM3bjnFMF/g/crryYEXUAtEQDFSEYWXehcy9wEDmPqBc4Gw9hVXMzxkqAAMUysj3HQcrIjSDGR6vvVeTXmH10ZfVh5/pAscpTJaLJ7agM1/jMCygxQCIBAs/VdHuDDqJAXLXzJOpzkAO6QIqGVQgBYbHmGkLqJO7fILpBpu29nJ3Vbf4GivCXHFVx/N8Q+cdfsK2LFwDXOtk1If3wZBPujla0KhL1uBxTUPK6dWRqg5oTw3Y79Gc8yhi+RJhoVYMQERTOLR7FsPn+nmN2alak95kYIw+UJ3eOngWjWbnKpysMHPgJ4GjVZgurD48pgs7TmG6IMJyLgNMAAM5G+kc5I+2oebDmtAjcxmCcOLmlsoYQAoMD7W0YOeX8z3SFmnoeklBXXU8v6w0rTz0mG1ZuAa41mHqQ1+FzusIT2/SWNbgwaoCiqEShzVFMtgDLSmnsZaxiUxSmVkKWLAaqcBO0Jps6vyU1cFdHE4FFAAHOFRlRGYsw2MMQ0SoD2G61PA1XbqwyQNzSEADJkkYuFJRDeefKcyWF5eeRK2HNaIchItT6SfCPgWChz4fovcYb4UwlMCLTbdXMkmloxRPQtzQKw49alsWrgGutVQfxsgL+z6wuJZf2PZmfjjMmm9ezS8VFZI1ePAgRRYnErB+xmHaOkPbicxO4Yh0m3A6sllSn9uF41uKqMqz6+oIswPmB8wQmCMwS2CewEwBMGC2IDKD0O4Ifg9gGQLThUEjTRcojbM5W1U4OzPti2AAnj34HHpp2Ul032yuTI9ktgggSGWbAsEDuRyxOphsE+uLNolaHYbJb0I6u+B4gW/o5R88ZFsWrsHEHZcJWKvqwzNtn+HRe2k1Uue86CnrcI6nAgrTPlmDBwYPD6I/cL4EMjmRU4HcCuRYINcCOReIfiAH42nOxUBOBhycPTjvoxdHS+D4hAMUjtBBDAU4RuEgFaYLQ0OaLm+y6RJznPJ2CMkiNAzHak53bheelbGBHBv87LM5tX9b6snwAECaDG3m5J9kACAFgkd84aY8upW98eoNYnSUuolKgIdUHaO3XEjLPngg5Zb/4dO0+ehrxrbxk/4p96uf09qPevgeZ/WRbp7jBG0rzxVqqyDfW913/ccv+54bjievw4Qdlwh4mNRHLGzLsNdLFsIJLucrIREQJRfSAZCswoMnnCFpC1mcyOZEVicUAbI8ke2JrE84MbtyKPUZhkc3Dq0ixIpQK/wWCL3Cj+E1XWoIxQHlEXOcMlSe4v2Rgp7zUlzZCIXTjaMomZ5ox8qm5RtNqNfykwRAfjnWnWhXlOCBcKwsoHsdR1mGTvTOgcCkK6PqEI5Sx1xBe2F5Li09eF/K7dPvtlLQK+/wkyn3rZ7XJ9+u8T3MwS/neo6BhLf//d+/A8/r8NdL03JeGz7uE3qsfZ+/I441YbsLD6k+DFmnMDMx61mFg5o9DHjg72IHD5bz1TknA/NHoD6QlHUvq4+/svpAshbCplAKCKMinPoc53xAfSDJK2a6sPrwmC4MCtV06cNzYzqMYzNBh0bMPGL1gYltGRjIsT45MvSXKdWo94qTBECum8Ir8l3Hvo8MHDNl5YGlE+AgdWbPriIkiqk3FPIGopgsgMeSg/ek1FYdfjx04Bz4ck5Kfevn9PG3q31hYDrGzk/HBMIDH2479nqBzm3FoUfo+/98Fnic499tiR0D1xpmYqDpwsqj++KTPaYLlIY69UD3baUKkqwqDwweDsv+ZU4TQiJWzHRh9aGbLkjkQlTkeYYHUtahPvqw+hCmC6sPj+nCpkrPzXWEUzXnJSfLNLBBfWTS78HO2K6LylOflScJgDwwN3N+j5Th8djg+NqngIi68rqIsmjFftRUdGmyvLC8NHXnG3rxgTtTans+mxQ6QH/479cp9a2f08ffvhsAj1nGYwTtg87+87/vOET9ZMrnd/y7zYHfH2CBP0l+F1xrXPMopsv2bd08D4N4VC3RRC028OA8igvGtRCmC1LBY6YL51skmC6sPmC6ILX8JYaH6jgVpgs7RR/Jb0DnjmXzBCFR5InAp/G81xFrBAmiLRlQAggp/2xsPeq/6iTqy/AAQJ5cWN6J7mTgeCnBA5mGUnVAeegFf1DmzhRlcXI7XHPFNVlwQy/cf3tK7R///iQUHthg4yevpNS/el4ffZPve6z9X8w09r/4wD0Udo5f/Ws/D+57kj6/3Z9NDPzuMJvWHnnJ068DD019mEyXBTk0a92VHnhAbQTNXUoFIFlXHpxPgZCtNF2Qlh4zXdj3IUwX9n3opgsyRJEQBsdpDwbJDfObiHwRY/QEA7Uw1AfPGK4/qAX1XJFLr+SdFAPIw/OKmPJQQ7TIQEQmonrzTHj352Z4aFEW3Mjd+csu2N8l6bb+416RwIGNjv1jU9L96+f00Td5vsd7/4sZvv3nHe4aalp98NXCpM5vzZHuoX3u/uzthD5xraOYLqZ0dWSXIgkQAMGDw7TiX7IAyTo88PTl/Aqkqf+WZ6gKxymiLtJ0Yd+HiLqw41Q1XR5nP8iVM5tSS05Fj6WO+81gRa7Ic27hniCIpFMNcCi6AYPjaTY3X80vRQMYHhIgt0w/zcmgLSrKAws5yRXgUPdSD9Ei3KfWJ1UTw3STBTf0/PdvTrod+WZVZHjgKbzs4ANJH0M9r6Djvf/F9MC+tx8bHnqumz55NdL5QaX88z+fBvZ37B8bjX3hWvuaLobJcuoyDTBLVbWZjmzTQoEHBhF8Dsi54JRzJIEBJu15vsuFPGHuR5zjgUI9qOiFnA+Rvo6G1PGovgr0HaY+MH8lHSFUDs3WHtSKnlxciQa9W4oGclMB8seJNZws1qICjx6jltL1PRxnqe7vUEO0esEfNUQrVIerPOa/fyPf7NEbbPiwaIY+uvawzE/mGPq2R75ZGaA8pob2HbS/9H+sYP9E2Dke+8eGQHAALLg+pn7i8Mj19Xs4dU6dKuv6TFs1m/huXns4WaWhb19o8JADSZ3Dgpmoav1QOdclldAq0s3hGA0DyA2NCzaoWeXUYXD0WF6BhqwpRYNXl0oASLsRmcsyTcnnEU9ZTnSe4YaL7O/Ak5DbiyvL0Lx9N0Ruuz4dG/ok1zf4xw8fRe7fdC5Hvl7he0yEQsPOfwGrK5xD0OvL7/cRtvPrK+x7A6irP3zWuD+usYCHyXSJ6PdQ8z0wn6nYwyMDT+PYEz6K7wOJY6meA8Ou6evNaUB+GRq2thS9zvDQAdKL/+dCNWVofktK8JAT4aA8EHVRbyLf/A4ff8eLK8oIeMzdd23k9vW/PkgaHthhzZHnIx9DP58jXy8PgMeUSP2uOPRwqGJCzojpWrzLyXBhamvnp6N9zwPXGNc6qt/DlO+BXB7k9DizbFeRvnRosjApdOWR6sCNst8vanFWaUjkBb6RKFPu1eOhaDPnivxuQl16c30pGrGuFL3B8DAB5NopHP2BuRXlfFPYJml4IF0Zcxyk510vN+hZVkGZz6KnpEuTBTd0D76x5+y9JlLLZwdk0Ovf//uH78eImEQ9jr7dh18v8+137+eTI/e79ejgUPBt+Li3p7+F799C//z38cD9jn67LvAccI0lPITfIyTfw+Q0xbwW+b835fZYeCjrsMDceSFC2BYzfqMOXFYb9Qa3pmeWVKHRG0rTyPWlAwFSeyAnrKVzxq52nknDAyuJycWBTF73ISuaeJLDwpyleCLixu6xqgzN2ntVaPvgqwW+g+jrfx2kHcdH+n6OJ/fC/beGHsN0HsgI9Xvt+XxiUn0G9YVjAIBLDt4d6/OTb9cGguO7fx9jH0dn33PAtRXwkKaLX76H5jTttqiMR1WG/e8tPLRFnJBN2jMkcexpVh9h8HDVxh8m1qVR68vQ2I2laQzDIwggnafzrF3UIQnruwCfJw2PsMzSaMlhcWdpHB5laeaeKwPbbFYnQdJ9x/ERHJ68JXSbsOOYPj/89RJ/eHw2IfTc1T7xPb794UggEL74fg9hO3ynoBeuxyqezh/0nXqsKmuAh7/TVC1PCAe4hEKY6rTw0OCBqf0vhsDjeXas+q0DA18Fq436rDa6L69Cb28uTeM3laZxDI8ggPRZ5dbyyMSUfwU2ScNj3IwFMZNFt3uxBois32GMtCjzWWJmS0x5lKWX8srSjD1/8G1Bkv9///cD2/vXiX2DIhtf/etA4DH8jn/468W+Yxg5FUHnbfoM6e8456AXQq5h22w/Pjzw2LimgfAQ81y04sgy4mKYJCfNVZO/y8JDg0eUqItfujqHhWsNbENXTqrLwChDk7aUpokMjygAOf9NjuJksI6HVDNJw0Odhq/PsPQL0xozSxFlcSMtkNQv8dMRN/r0PZf7ti++3+071o58syK2X96HTwQOSswJCTqO6bNDgfAYn3R/OMYmnvVbkBfm24R9D1xTXFtptuCah0ZcAhbFjs+kTnSWW3gY1p5FRCUsZPvbunHzAiYKVy3rOLopvb6uIk3dlktTtpamydyiAKTztMw6SVUzKGl4qGnKcJ6pyxFiPkRsMpy7xEK85KCWlq7Cg21yCY+efLNP2/2bhLb04L2B42zV4cc8+3zzw2Hf7Q9xRqfpGEHvYR+/167P3kq6P3msoH6DvvB3/z7KZs3VgcfFtYzBg6+xE3GJCo+TRMgdUw1UKKi5HgWt7VGioy1S3kdJGEPBHpgYvARl7UFt6Lllp9GMHbk0ndu07bmRAXLbTAZHhv0caYMHpKt6Y3ngoUZaTHNaBDycGxoOPRUevfLL0dTdl3ravi+m+o4lgELfftuxob7b/5fNhRl7fp+wj96H+ncwPMYm1ZfaL84jCHSmL4HzX37ogdBjmuHhE6415XoY1rNV4YHfk1Ub6vYnBDwu4TVpUQwoSH2wX6QOQ+OWmbVpzs5cms1t1nu5DkAYHlEAcses7IIjpXVb1Nm0OjzWbH4oqQSxIHi8s+tXJNv03b+lf//3W18Y7GC7X90ev8/Zdw1hkPm9NvFkOX2foL+DojyYfp9MX/q2C9nJG3Su+nfYemxw6PEA4ALDg5UHlghVB7z6/7fwMJgpevQCKe1B2aZc++PySQ1pwtYKNH93Ls3blZs0QBCFyabiSNnnod48sH/VGyup2bR+yoNvetz4vd4tR5N3/UK0NR+9EKgiZu27Orat3Ac/g0KiyOZUtw37/eBX833P4T1OzgrbP+zzqBP9MEEvrC9cOwEPbolmSxLKg2fXovq9HzwKmmV6QigPP6cpq5G2I5rTsPWVafHeXFq0h2eXMzySAciwteWp8RAu5pylRZ4KvOhT0JPHFx4h2aUes0WBx8vvlqdJO39GQXUrMGEN25gaSvUFvRYeuNV3X72/g1/NC4DHyMj9+J3rO7t+HTp9HyeA/A+/PvA+rllK8HAjLmpNU/g8MEPaBI8bxDo9BUtRPyHgAV+GGq7l36u/di6b62fQ8vdzadk+rqTH8EgWIF0Xs6JBHZEsr09bIJ9HSvBIxuehwWPEljbBkZPDj9DEnRf5tm9+8E9lR3JX0L7qZwe/mutvNn36ZuR+/I6Hc4nywveZwqrK1E/v1eVTh4dPSUK9mjr+/zJF3cIjgtkCMwYRF64yVoOh0WVWXVq6ryyt2p9LKxkeqQDkT5N5lixHZDKydEMSSWNJR1uCfB5pMVs4QiDNFjxF13zU23dMoVLWhPcuDGzwbfi94EeZzE/rsD7w+YEvZwf6XKL04bcNIkXJvHAuel+911SgBHgYoy1JmC3W51HwDE2YLVxd7Nfjm9Cc3RVp9cFcyj+QS3nckgXImE0VeDJc4ZkpBTZb1On4mYy2QHr3W1OVpfxR/5Dr1wtpyQd3B7b8D58KHJdrPnqe3n6vY2gLgsf242+E7u93jJl7/0g//PebZNghtlXPuw/AsaZ8VuCBqfgyUcw6TAOUB8wVnpTWdFhrGrmpKq07VIbWflCG1nBLBSAPzedoSiGbKQWGh5rnodfySFeeh1Qe03b/OelBlewOqI8xfkf70Lb/y1m+XW8/Pix0f9MxJr73I/r0n9uSPWWxPaIzs9lR3HdtBUoOHsnleWDhLr88j4LW9CixPg9OS6858FzqurQGbfqwDG08XIY2cEsFIIjCtB/JxYWQv5HhdPNk58Ekbbao8NAzTPfu6EPPuIWP1fT0ZDJMEV6U8Djwpf8kuJRGnM9Os/b9icbtaBfY9n850/eQ246/Hrq/qf9dn40r0Nf4nOe/DFh/qgMP+Dt0n4dqtrgT46IniTkFgfQkMazPI9dw0csxJJvzUeLgAeclJ3zdOLMB5X9QnrYeKUNbuKUKkL8tctVGOiqOJeHLiAqRpOGhzm2Jmp4eW14ywtwWwAMhxoFc+j5bL2SIvrWjTWDb/+WMAHgMCd1f73/FoQdDv96240NCSw7u4ByTBHi4YVqZ55Hy3BZDNTE7t8VgqmACG2eHthnRkqbuPIXe+7gs7fioDG3jlgpApu6oQB1GFU21UaBoi1q/FIs+bV4zwyNrCzKrFjkJEh55h/1zO0JHXZIboHTf+B3n0djtLX0b6pT6vZC0FbSv/tm0PZeE+jlwPOy38MAtod9m3v47FOURz/GIBA+uZq9PjJOzaqEioSalovDUMeUylPB/Jas2SlyGKauC2myiPLKoDu05WpZ2f1KWdnJLFSB3zuEV3uDbKKJqo0DwwOJOKASDJ5Bp4eP+y2olVfxYreeBFHUJjyBHaehoSmGDlYcfpTHbm/u297+YFgCPQYH7qv2O23EufcoLMQW9MPMX28n9th4bFLj9f/73PSGk7ZgtGjwwKa4A9TzUdWvxoJCrBF7fI49GvuOtmp8sSIq12QL/A/shfvZWc1pzqCK9f6ws7WV4pAqQNzacQo1fZ2iwgilqvg0/MyZpsyVsmcnReR0iLbugVxJTE8Xe2ZV5R6k+GjGNf/S2pr7t/YC5NVuOvRa4r9rvzk9HBYLgv//3L54p+xtPfwM2VqIPAwowo8PP/7lbRKf8EsSSrSQGBYkq+CoQ1OUXsAwD/k4WGCVCeXBx5IZD21Cfd2vQB5+WpQPHy9J+bqkAZMauitQI0HiCK7VnsOpXVD9GMtslDQ8s+NSpZ7wMoV6CH4sFPc1pzZC+MltR+jwSFnzySVHf+5m/f+Fjrqo1adelTuPJc5N3X0ZT9jjtnb2/EW3+/k7Gtu7jFwMH7pTdF9OobY2Nbd8X7/juu+XYq777qf0t5bBy2Cvvw8difb26qRIBHK9sOJmGbT07MGyNfrcfHxOHRzI5HqbsUv4fvpl3rgcOUBpQHM4So3mEB8kJBQ/4NlhtXDOtMW06cjId/qwsHeKWKkCufqchlerVgRsvUZnBWqPJACGZbZOGB24WNdavh+sQ2kumerpa0wOOvVfX1eFlGL/3HWOz93UW4cl+XOsArf96bjy4MMAw0NAw6F7bVJlG8oBT25ht57AD0r8W6LZjQxL2kfvv+2KK7zltPjrAdz+5/+RdP2U/x9eB7MAxsD3OXYJDwgPfcTLDMuw1Z99twuFs8ndEirTwbGiAHw8AfV5L0JIbqUCkWJktXGej5fDW9MqaGvTxF+XoI24ffl4uJYAMXHsGndK/PZXr3YGq8M8y/FMs+5CBiEgm+0wJHmoJfj1RzC9cG4u4yOUmlXVb1FKEyw76Fzj+13+/olfWnSrg0VeFx3oHHhIgGHiv8gB8bbPTRmytH2twbvq9ABZ1W/X3vV9M9t1v09FXfPdDH6PYHDr23aYQP8f7NGTrmeJ8ce6q6sD36s/fEaBc/VGwegJ439jU2oGH8HckV4JQXbdFD9MGLblRouHBq8y1YHDkHTyFjn5Vjj75slxKAFnzQUVq92YLKtunA53x6nlUfVA78bMc/42V7DI50DPRd0rwUEvwm+QrVllPZsU4WUEdfo+vvvefi7Lh49dEWFLAw6M+vPBQ1QcG40C0LVVo0NYqNGHnhYGDeNHB22j4ljoJbe/n/otqbzraz7iP7AclA4Je//2/72ncrvMc2BlUh4SHVFuHA9aQwXE+Y/9H73eruPBwix+7yy4ks2KcWr/Uuz5xHuEBkgowipXPA07R6xpx3kYjOvhpBQENwCMVgDwwvw6V79uBTmdYNBzWlurzWre1h7QTAAFMIq9GV4TUSUrwUCMupgrqUZ2mcq1aWdfjra2/Chxko7d1EPCIASQJ9TFwSxwgR771X6ryA549+8aWWgktaOLaxqN9jfugn4UHbw2zNGjx4btiKilIdQAeUFxDN9UP9X9sOzZaKz+Y3JoturPUsz4xL/g0ZtqCkg0Pdl6e+2ZLzjc6S5gnMFNgriQLkEX7KtFZr7WjU145n5qNaEstR7ahpiPaUKM34gARZkshzo5NVZWkBI+wdUuXrr/Zs/wCHKe+5QjdJSdhurx33H9m6UffrBW5DJjD4ac+TL4Pab5I9QEFsvBQcO7EWzta07DN1T1tz+cTfCGw8ZM+Cdtj/7ffO5/9HF8FwmPX528JZaSbK6qvAyZLPzZZBDxc1TVxZ7j/Y/be24xV0z1rtvjMptWn4gMWcrEv0/rEqaiQIuvzYHBcMLoFLdlXRThE4RhNBSAv5/M6sbywU11e8/YCXgO3/VutqO3o1tRCA0hpOExTWdaykFVISvDAjRK3f/NJLwoU3e9ROrZe7Surgx2lC/ffL/IYAJCo6sPPfBm6tRp99x//CXdrjj5HQ3dUFe11t+350j+VfMPxnrHt5H5v7DiLjn63PhAcX/xrT8zPEWSuwNchVYecy4JrkP9h99D8j9c3tPQu9rTcqSWLBcd9q6Yb0tLV2dRQmzBjUgFGkTdbGBwdR7WgzUcqiBAsIinJAmTjhxXp4nHNRWQmt3dHumhCC9FMAGnAJgwW3E716V+Y+6UMD93voWeaBvk9TCHbRe8/7DsQ4ATst+YUUasiVH24zlM18iKjL6r/Y8tx/8Srb344REO3MzzQXIjsDoGH3E7ut/Wz4JXhTH6OhAiLcJKy6nDNM6k61Ilwof6P73ZTr1WVApeZxMJcUId+67V4/R0Fr5ouAVLklAeDowODY8PhCiJnA7kbyQKk/2pWGzJDlE2Rqq+0p19NaU4XTzIDpNYQzu/o3OTEgofq94Cc1W1gFJEJC9niCSj8HtyOfuufdbn5k2EifwHwSFV9CIC45gHMhAl7fhz41J598Coasv0Ubg5Edn8RoDyO9RTbYFvsM//Q9eF+jkNmP4c0V9QIi0l1yIlwg9bXC/V/bD06WlxjXGuhOsRaLdx8TBas+qcqhLD/daoKpEjBg9PBMTdlHYMDWaLIFk0GIHuOV6Sfu2ojliHK4dcmw9vSZVPP8QXIma/xinHFMEybUgFk4zwHw4LX8en58YQx+D0SQ7alqU/eGbTyg+edduh5XgEN7QVaxfNbMMcFoUfMtEXqdUx9SPMlIfKC3A9z9EX4P9wQLgCy6qPHaN3RlzxtPf+9/thLBHgM3sbwQHOBgPdNbcaB3zmg4W2xT97HjzvbaX3LYy3/8EGjg9QPHNLX0UdOv3fNNwFTviZjt/1EXCc0XDNx7fga4lrK6/rKu7UZHv4mS0x1cMV0+KxUIKhr9aTL34H+iww8GBy/HN+MZ7+WE/NSMD8lGYDM3sMlAbF8gj4fhZeb/Omk5nT5jGa+AKnSn9ezLQbzWEzmUcpmC/75qh2sz7DF5/DYqyHbmOPUp6YpFiPS57rIyXJqdTGpPlA9y+w8DQKIk0AWUyFuFEaGchHORRu81QGBqQmg+Hwmtud9ZT/4ib6dFj+u9HHopoquOFQnqWOuRJh+r89l4esK1RG2uDX+V/ifYeU/CQ+YLLfzEgvONPw8wpT8VJWGvl+RgAebFu1HNqf1h8uJmbDvfVwmKYD0zKtJOVibRV8ykh2g5TkEe/WcpvTHWU19ASL8HUWsTkdUP0qB4DF/0RwCNJAoZjJdULbf33QpJZx2jukSd5zGlmNQVpGTk+VUgAjfh+48Xes4FmOZp74KxJtE5kRinFCuDhEHJC5MTEDBe6I50PGDhoyoqFGVaOBwIizSSSy/t17oOGiZBVNuh9FkMaSkq4tbd+qZR4MnLCo58OABjkpfi9+vKKbOYwp9VIDsPVaeOs3gFe6RVo60dT3y8ft6Irpy7bwmvgBpw5GXHCyGXchRk1SPXyB46I40PVX9wHsDXHgopgs754LmugSpD6Rd+5ovMnnMhYcDEMfhqGafeh2pihKIJZMpEEFimZtcpoPB+Le7vao0VGiYwrGJporiIHVDsyZwJMyejWWUuklhDF9cS+PSknJtWoOjFGvvqApBTUlPx3yWIhNtwYBnxTCKSwSiWA+K9ngB4kytN5kwKw9w0R8Mer+sUO4biV83LmhMnbj5AQQh3MJaNiFVYBRoSr4uPfWbS4+6wPlWEPWB9Gp1qr7ZfHHDt67/Q6auIzdCzH0JBIhXhcQjMnGIxGHgKpMESKimiWOe+EFDVRtGcOhhWb3MoKFCuikVHQpOwEM4Sr2+Dj/VAZNFnYKPfJ5bOCwrCwAVtOxgkTJbeK2Tm2bWE+UBUSYwKkBm7TrF8W8EzYDlMoStWVXctqQR3byokS9Acrq2K5b5HSkv+qTfAKqsxU0G55q6zYYtf09OfShJY5hGri9FKdWHHn2R/g84FdW5LyYTRp1EJ3NB9Pkw0icSg0nMtJEmjvLTVS3qPvJ3mTHqBw2jj0Mqjhg4fPwchlXh5ILWcXCYfR0J4VmDo3TW/LmiZgv+rzfxz/Ez5qfNZClUhyn7J+oNbsPVy8uJuqJRATKdq4QJ/0aQj4JVBya7dVnciO5a1tAXIBe+zVXQ0VcxNVkKFG1RARGva5knnGt6AtFry+vTU6K2qTZNX6znovg+ZOhWXQQ7ACAy+uLnQI0pEDn7FmaMO4FOB4gOEXVinQkKQe85wHAmt+nQ8KgNZcJbLJdDySI1OUijmCue2bNRVAf/b3ourepRHXoioL6oeTqcpoXmMOW8il4c4UMlc1Q0jwIQlBcMBQdAwIqm47iW9OCqBnTfirN9AdJgGJssxXAyXFrNFtxEcKLBmYYn1C2982ny7HkpqQ8170P4PuRC2AIgivkiiyQr4Vs9/yNIgahmTDBEnMEfh4EDBRGtiQFCgiIOizBoxNWGM1NWB4cMyeq1SSU45Dq04eZKRNVhmH6v5nbg/6rXbSm28GBzAxW7sHYKlkCIApApO6pEAwcrmtM5b+PR/Abc6vsC5M8cgcl5jDNLi2FKetrhcWjb1FhpQoTz/NTH0yyNdfWRkPehqI8ozlOTAzXBhFGiMKofJAwiepaqhILfT7m9/lM6bBOgoYKDz1FEVTRTRS7k5AFHmLmihWaNqehKRqkentVVR7odpYWaYcpRkPvn1xSLLkUByKTtEcEB1cGruHWa35ieWlePHl9d3xcgiMLk8HkUZ5MlbWZLFPUBL75eHNk0YS4xdOuf++H1f3gzUGMAUabwJ5oxiREZU2TGDwpB76vA0KFhUhuecKyWyyHAodcmNURXpLnicZIGZJMir8NU9Ef3Y2VCdRSKz4Of9FgndvEeZ7nHMIDM3n2y4xyNkofR5nRC6LX7pjr03Ia6vgC5ZVHjEqE60gqPKOpj2KoW8ciL6/9Qs04T5rxI56nHfCkjoi9qpXURgTGYMHIGrppI1ldVITFfSBwiqk9EB4AOFr/P5fvoS4aLnahP3ERxkr/is2Rj4Vi58psbVYmqOLzRFR9zhX1MnoWs2VzRIywY1ICFjLCg2DVMmHSYKYUebWF/xPXT6on1YbFObBhAWg1np2aUqfJsCqE+R6/NdajXltqBAPkRHKUlQHWkFR5R1AfyPuSiUJGdpwUFiJJI5oGIkg8ifA5KWDc+4L0O1uRgEa/+JafT+zlFE/I40gQOaa6YQrNCdRjWZfFUSGdfB2ZPZwIchaI8OLrRL/9UsTJ9GEBun1032pwTVjNn88zY5zfWpQE7alHfbbWppw9A7l7Oq75h0lwx93WkLVSr3lhQHzK0B9/HXf1XEd5Tt1GzTiVAEDaM1/swR1/8HKgmBZIQxlXqgIiBKsO5SjlDObC9IFEUiZtw5gGL8T1XYWgqQ53cpvo21BmyplXfhHM0wccRLy0YUxw+fg4JDm9olmuUMjhQtEkHg1pqIZOqozDgUalfe5r+Xjlaujc3ECCD150WLYzqJpp1XVufXt9TgwbtqhkIkJYjGRxceb24+zoyAg9dfdzYK4/6j/PmfSAJCaFb05wXPfNU+j9kxbFEgCSaMIF+EAGReKq3ByJ+IJEKBTBwW8wEUd7zwEdRNfCzyKn0idBw8jdUaCT4NwKcowngkGFZ6eeQmaSauWKaw4L/HaJkiJZJk6Wgy0mGKZashmrZtGg4tDUt2pNLixkefgCZubOC4+cIUwfwg7CD9O/vco3a/dVpxL4agQC5bh77Onj7kgKOtJstuFm8E6nMJfpNM26l87TAAHGXq4xnomqOVHeweiCiOlVjqsQp+ac6Wf0A4TFHNF+GWoMj7teIQyMQHO4KelBXTjHjeOo5JhB60s+TAAdUh76ANRSiMwHOgcdt/PvujdMzZrJkXXmcXYVaD29OC3fnBgLkV+N5kLPzM3CQc0i2wett6W8ckgU43jp4ViBAHl/DkZUoQCpmCWMFmtvi92TRcwRMdnPcfPEP30oHajIKxN+MiQaRBLNGNXGS+F3N1UgGGglmijGqEgeHMbISoDj8zJVeo5cQlKKTq5NHIwq4GlyY6igseMxnePgBZMTGys4gDxrEPH2+FhcufnptPRq6uwYN31sjFCBiAlwYkIoZODKiPORNo3rscTNOnzs34Slmir54w7fIQHXnZbizb4NMGJlIFgOIqkIQkRH1QOIQiWWm+qgROeglUAQQFIDIv02gCASG6hB1Q7AqNLwzZDXFIeesSB+HYaq97iB1llNw/BwwGY/tHu35X+hOUviq0lFmMAwgWTVbWHm0YuUxb1cu+QHkorGcvNWCa3OYBjLMGK6k3oSLGL/A4dhX2Dk6cGfNUID8fjqbKlhCshjCIeycM6I8cNPs3zJNSN8gGYy6EcL/IVPXZfhWc6CmChBVhcR8ITpElOpk0v8gSx3qpo0HJqrvRPld1Ntww61qf846srLF8zZi0FB9G+qaK6qpkgCOOFj1yIp0kEpwiOgKt53be3jAgclv97yyMv5/4pXgkOcRNvDT8XlW4QHnJk9Em7StnBEgw9b7qA74NniiGzJHr5rdlLpvrMPRlDrUh6MqYQC5aSGbQFzHNGwQFtfPMwYP3FwTZ86LL4zMcvi+ASsTnmi+/o8oAImFcZ1EsoRCQmo+iKtCAiHigiRBkUhlksRPAQsPMNxFqHWl4UJDVRsm/0YsASymOOLgECUFUWDJrdQWA4dal9Qw8Q3/o2c5p+NGLiMJyMNsgfmSDjBE6SOr8MCTn/Mrbp9dk+bszE0AyFXvcF0OhkRsICO/gwd+xb7n03mcEfpgXgPqyqbKs+vrRgLII5yeLmqTmmp9lBAVklF4RL05fbNPgwCi1D+VjkMdIDEzJgwiHjXimjUxleBCQMIg6Ke6j1siUI2eyGQ2EXr1hYa/Y1TWIY1NsVeiKjFwGCIrMFfgY9IHdBS4R4FAqttkHR4wPdinMXZzhQSAVHuVp8dfzSqBc0FQi6MaK42fT2xBdy9tSA/wJLdH2Dn6GKecRwHI05yeLup9lGBwZNTnIW8oXRZjnsScBYn+j/haL5oDNVmAuNmoqgrxhYhJjbggEengMR+J9JUYwKJtI/eT6eR+wEhUGonQUEOxMXC4BYxjRYzDFAeDQ1+DBf8b+DnuyHJ0pdAzTPHEZ9/HeW+eQ1O3l40BpG9+VV4vtqNYkOnnXHP0z3Oa0E0LG9GtmFbP8LiPk7uiAuS2xVxdLGzavlUeU3KiPnH0GxUhQdMK674RGBNAZAlDV4HIKuwxFaKmtLvT+nWIeByrCkg8po2b+i4hEPVnTF0oCiMGDNenEVtL1vVryFocRmgojtHI4DCUFcT/LCrQo/5/U90u68pDDlo2T66Y3IBms/kCE+b6aXXFMpBXzGwm/Bp/mduEbuAJbpEAws5T6QO5HM5RdqqWdMWRsSQxvxvJK5Hz6G726MOpqm+Pp6RxAp0LEE8eiBqJMZkxbkHlmAoxQcQ1aRJAosHEAwNpdvj9dPMzRO1VgEJtbmkBsQC1Dg34bdzSgaZiPvHFmrwLNhl9HAwOZJCqlcEkOB4euCLmIM22n0P9fxcaPAARHuR/W1hNAOSS8Q05/Hou/Y4HfyoAeWpdXTEprqRGVfwcuhn3eag3S/eRS+lGt+4HHHT3spdfT1/H9vE1X7wmjCeRzC0k5InEqOFc15nqZKXGHapRQSIHvARA1J8eUCgKw1dluI5eIzQ48UtWPDeqDcW/oS/aZAIHwq9PDYOD1MnnQHvgtUQndqpKItn9ChUebmr5c8tOowtGNSUU57mU11eJAhAU+pE+kGvnckQF81VKYB5HWBQoq/Aw3bx4CkJG6zdeggmjVSFT58IIgBhUSKIpEy8w5IFITJHEiw6Jmbt601WE/Nu0rVAWrrpQFEbMNBEqw0dpaCaKE4bVIioqONw8DjnZbVz+jxMUh+68BjgQojVd+2QhkOr2hQoPqA8AhCMiDYe0EuvJ/pJXdosKEExya4G5Kkg5D0tlLyE+Dh0mWYWHSTbjJn5i6HJjUlL+pns9JoxnJm6CGeNNKBNZqT7+EHWOjIzQ+MPEAUq05tRcVc0RDyxiKkPJEI0VKfaureJZ2U2sc+NMHhTNrXoeVxtIAnNmyUK1mQZzz1FL6WZ3mQxc8/s5bG4yG1MFQSr7FTo8JEA4MlKZF1/6GS8JGQaQzlwNXUyrh9ooQZPcwlSG6fOswwM3GUwVmCxSOkNGPzrIrEBEAWV3YDirmsXroPqaMYoKMUJEMWliZo0SpfEFChRKSFP3jf2uKwwXGPHQa7z+hoRGqNqI5XA4maN+4VioPR0c8DeZHNapAKAg+xQJeEiAcAJZtYHnUcfxrRIA8hs2Z346sbmYei8qnqMexwmqNtJehjCVGwiTru7sF89ABUhgwpiehsiKRCaqx5GqmjGqClF9IUaIOOvjxkwaDSQemIhB7ySfRWpyexMsTMDQfBq6Q1TN3VDVhidr1E05B2T1/4M0E1XFcWe/PFqfn50M0rD7osjAAwApn+sUJEZt0Z4dBSSwSLX4He/BPIHSKOG5G8kokEJRHvKmwtPvDg0gfnIa8zHgBHTqoDqOVD8VEuQPiSkRxaTxgMQAExn+Tfqnu4i3R2G4/gzVERoIDaNT1DVTOKKC+UGHdw5OAAd8GVBzqnMUIfKVy2dlLYO0WMFD90tAWUSpIlZC/RlRIFKo8MDNBaWhzq2AAgFQ9MWj5I0oksn4aess5eBGY3xMGR0iumNVmgjCN6LBJEGdKCAADPya3M/z0+0/ZpLIRK+YIzTRGYpzlyUDxU/VKcrQAESnrb3c6BjFNVXDsbimUHlFwVQpMqHaE3jQRwFDlG0KHR7SiYqQofqURPYjckNMT6+YGROgQlR/iBcijmPVDyQeZRIL/cZNHSMc3FXZkDYeA5GSCeqBhg4MNUPUdYh6oKH4NmTBYtQd3bylq/HaLFk6x6jmMl2bI0xlmD4vUmaLhUnSE/iKBDxwY8E+f/L15VzGMJ6DgFR2TNzyCydChfRfVisxIhOgRBJBEg6TBMXgBwbT+yZYGIDhrzTiJgr8PrPWXWlUG7h+fd9a4inoE+RHSmWwp3sfCw/2tWQIWjfffDMtW8Zj55NPxM/q1avT999/T23bsv+Gj/n444/TyJEjC3T8IgMPeWNiAAAa8UiMk4/gZ8ZgWj+yUjGtX3WoCn+IDhHFsRqr2g4Ha8zJ6oREpTLxKBSZSxLwU9/P6ctt7nFi4VZD2NVrnjjQgHmG7/Vm3rmEAtKmAQxVAeWmghc1VFBGsDDzOMJgY+GROXhIKKmAmDdvHr399ttUvnx5GjJkSMmDB244TJxTa4EIPwibMaOmLvAtUrN3Rx/hUDVBRHesCpPGAJJgoCggUKFg+t0PFH7A8Jgm8ZwN+DWgNkyRFDkwUXfUG7XKo9sYvtmoBBYGh7DPLTyyCw+ApH///gIaUCAlTnnIGw5PU9T/UP0gnXmxZVS5CipWA4ggw1LmPjhOVSU6o6kR6V8IgkmCWpAQiPpTSeoyO0EdYMhEL0ADURTUOvEbgFBiKO+oFixGxXqApKiEYi08Mg+HMLNHBQR+r1q1Kj333HPCXCmx8JB+EBSnUc0YUZyXn6xd2T8SlCGJ8CXMme6LT3ZAwhEKHSQm00Z3VgqoyOYHC2UbfX9/56cTao4Bg88R5wrwBUEDZggq0uvXBBDBNSnKZooOE6s8Mg8X+DYkYNTfpd8jDD5Bnxc5n4fpaSXMGF65TFUheMrCtMEi26bJdbIfzCpFmjt8BlgJPp6t6lUksbwRg68kDAjGz91+ZL/xn4rCYGAgcjJkRRNRzVyvLapeCzhEYaKYrgPWV3lr2vwik78Rpjjk5xYemYdHQeAQtm+xgIcM55pUSCeepYvBA4iEzdWAcxURGll4WYAEOROxnBEzUBIBIFVD2M+42hGOTzc/o9uiMgIYmPxnSvBSBx+UBHw9XdgBqpsoUoEFwTPqQC6M7Sw8LDwiFwVKxw0KW/9ujr54B1I+ASKIMPQesyQUIvI8YB4g9ImBLACCAe7Oo0n0mahmj//vcj/9JxyfSOpCfoZeY8N0XQAEABFKA74eGX3Cz84czka2aLYKFafj/5bNPI8LLriALr744liT4cmwJ6n9PDmYFRvlod98kOkYWDeJgRUP7cp1Rx7ieTLzF81JygcAZysGNxQB/CVQKFAJKgjw94tLThYt5k9xw6nYDvvAb4E+ECXRq5WHDcTFS+ZQD8yA5e8FIHqhgUW0VtGwyYuysjRC2LkW9PN0Kg/AIuiFfIcHH3xQhCktJJKDRJEoBlTQm03fH5J+/Iz54imsKxEJkVt4EGIwpuspDdUAxYIW5KNI5rtCTQ0Yv5hBmEddkOPSQ1UaeUJ5AJQwX4qriZJp5REGDwmW1atXW4CkKTGt2CoPkzMRuSAwXdSntfidByP8A7fyZwDJrPlzI5s2yUAg6rYAABQGTCycsx45kecP9QEwAhrFKYoS9TpkSnkgBAnTBU9MQAXZlgcPHowJE+Q6WPVRcPVRIuCh3qwwVZBZCYg4Jo33KQ6QYFDicwxcDGAMZORGZGqVNCgLLME5dOIi4a/B8YXC8Jybc54wVbqwykAOByCXqXOKOsAzuV2m4CHzGFRAID0bpgteSNO25ouFh6+zFpEXmDQYrH5Pdjl4MZChTDr1zBemA+ADM2LcjAVich7AEqVNnTNX7ANIoA8oHRwDx4ev4jrNN6PCA8cHzEZMWZjxBaYzCYRk+s4mPAASKA75ksrEKpDUIVLilIfp5sVUdMDAidI4URln4Jqf/uqgxmpqGNhRmoyKxCHh07+rfqAwpPopLlmhycAhbNtswwMOU/mCKaODA+oEJs/mzZtj2+F3vNesGRcD0nwFABAmnaHpCVjqttdee21su8suu8zXZHrppZfEdpiDYoIazh+fQznhBVNs2rRpxnNT9//jH/8o5rTA36O+8HdB4HlCwEO9iaFIYNrAXIEywAD2MyFMZkWq7+EYUCBQNtLvUhSnyYcN+HR+nm14YDKYfMEXog+wL7/80jdggwGrAwemj9wHJpHfQMSAly8MYtN26EtCQd8GKeWAhN8L+wFQJrhJU820bxDwokDlhIOHfvNjAMPnAXOjG0//R4hXRmpUtREGDXVbqWzQF/pE3zhGUSvGk04QpNJXNuGBAagOJPwtB4gaqcFAxKBCbgjUxl133RUDBAagDhCoEj8goX+oGfXl52+BOpAv/K4OXhUcML2geOrXr0/YTvXj6PksOH/52rVrl5jTgn1kDox6DaLAQt/mhIeH300PhaL6OQCAoIZQsNw+LNM1lYFWEvfJFjwwSNSnPwajHAh44mNg4YWBbfKFYKDKQYqfAIIJPKYoDuCDl6pqTCpBQgjbqc5cAEG+TEoB5yYVi/q9cH74TL5MTuRUgKHuY+HBJQBK4sAsDt8p0/DAwMOgUQcuQKE+caEk5CsohKv6S/SBKMECH4Q+IOG/wAsmk4SUbpao5o8+0xXbSvj4RYhU9aNuY+FhB3eJhVum4IFBLJ/GqskA9YEBpQ7wqBEY1fzQn/B+fQBS8jxgKgA6JhDAiSpfukNVAifIuQlFIl+qY9fCw8LDwiNCRmRQhikiJn5RDqkMMPhUc8Qk6SUIMKDVz2HqmMwDqWqkM1X1QajnI5WDyelqgqAKRP131RFs4WHhYeGRJDygMGBmYCCFJYPJJzsGYdi2qnNSh4vsRwWLarLI7eV20jxRTRaYNnq/QaAwfWbhYYFRYoGh+mIyZbYk4xxUIxmmXA7VsSoHK5SMPsilSYJt0I9ussjtddNFVUwmZ60KJXWmsN/vqj/HKg8LkhILkqIADyRmyZcpCiIHvWqamHI11IEKH4Russh+dNNF5p6YnK3YR8IN5kuy0RELDwsPC48kzZZklIcKBZOikANWVSh+kJEZnPhpMllMpos0hQAxExxkqBeASzapy8LDwsPCI4PwwIBVQ52mcK0apoU/xU8BqNupURY/E0d1hgaZTDJd3pTlGqRGLDwsPCw8MgwPdcYtnvByPgtMCnU+CPJFggY5+lFzSvxS1tXELhxPj97oQEBkRgWNPD85FwbHNKkmCw8LDwuPDMMDgxWORpmQZYpiwAwJC+WiHzXpzBQ9kWBI1hwBtNQJe6Zz1NPTLTwsPCw8IsADAwW+DjR90lsyjkb4QOBbAEjQ8Huy/cmEsLBp/1AUUA964prf+SKsK/uGDwYmFL4vQGQCm1yjpaDXxO98bHq6hVOhwSmd0ZZkAGG3Tb2Gh53bYoFRaMDIVJ6HBUJ6gJDMdbTKw4Kk0EBilUf2B3wycAjb1sLDwsPCI4JPJWwgnYifW3hYeFh4WHgknb0KWFp4WHhYeFh4WHgUhwI49hzjxZesz8P6PArtyWUHYvGugmbhYeFh4WFNn5TuAQsPC4+UbhyrGoq3akjH/++Ky5wlIW0rntcADtNFk3qQ371gwWBVRcbugX7d7qAKFcpZeBRTgOaWLk1Ht79t4ZGOJ6ntIzk1tW7+AKpcqYKFRzGFR5Oza/mCA2MhY08dO9CSG2gl9Xpd84efUJnc0hYgxQwgYSaLhYc1WTL+8IDsrVHtNAuQYgQPqMVH7roqUHVYeFh4ZBweuMkObRhNl/+6I1WpXNEqkCIOkdOqVqYB3e8KBYeFh4VHVuAhTbJxg/9Ot15/KSGEa1vRugY/uaAlPX7/NbQnf3gkcFh4WHhkFR4l1a9zon4ve/NYgNh7wN4DKd0DKe10opLWfm8bQbL3QPwesPCwTx17D9h7IKV7IKWdLH3tE9jeA/YesPCwTx17D9h7IKV74P8BpqFCbkNvT0sAAAAASUVORK5CYII=" />' %>
        <%  if (_.escape(item.get('os').toLowerCase().indexOf('windows')) >= 0) { %>
        <% img = '<img  title="Windows" alt="WINDOWS" src="data:image/jpg;base64,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"/>' %>
        <% } %>
        <%  if (_.escape(item.get('os').toLowerCase().indexOf('redhat')) >= 0) { %>
        <% img = '<img  title="RHEL" alt="RHEL" src="data:image/png;base64,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"/>' %>
        <% } %>
        <% htmlDiv = '<table class="collection table table-bordered table-hover table-responsive table-striped">' %>'
        <% htmlDiv = htmlDiv + '<thead>' %>
        <% htmlDiv = htmlDiv + '<tr>' %>
        <% htmlDiv = htmlDiv +  '<td><strong>Hostname</strong></td>' %>
        <% htmlDiv = htmlDiv +            '<td>' +  _.escape(item.get('hostname') || '') + '</td>' %>
        <% htmlDiv = htmlDiv + '               </tr><tr>' %>
        <% htmlDiv = htmlDiv +  '<td><strong>IP address</strong></td>' %>
        <% htmlDiv = htmlDiv +            '<td>' +  _.escape(item.get('configuredip') || '') + '</td>' %>
        <% htmlDiv = htmlDiv + '               </tr><tr>' %>
        <% htmlDiv = htmlDiv +  '<td><strong>Model</strong></td>' %>
        <% htmlDiv = htmlDiv +            '<td>' +  _.escape(item.get('model') || '') + '</td>' %>
        <% htmlDiv = htmlDiv + '               </tr><tr>' %>
        <% htmlDiv = htmlDiv + ' <td><strong>Serial Number</strong></td> ' %>
        <% htmlDiv = htmlDiv + ' <td>' + _.escape(item.get('serial') || '') + '</td>' %>
        <% htmlDiv = htmlDiv + '               </tr><tr>' %>
        <% htmlDiv = htmlDiv + ' <td><strong>Firmware</strong></td>' %>
        <% htmlDiv = htmlDiv + '  <td>' +  _.escape(item.get('firmware') || '') + '</td>' %>
        <% htmlDiv = htmlDiv + '               </tr><tr>' %>
        <% htmlDiv = htmlDiv + ' <td><strong>Operating System</td></strong>' %>
        <% htmlDiv = htmlDiv + '<td>' +  _.escape(item.get('os') || '') + '</td>' %>
        <% htmlDiv = htmlDiv + '               </tr><tr>' %>
        <% htmlDiv = htmlDiv + '  <td><strong>Ram</td></strong>'%>
        <% htmlDiv = htmlDiv + '<td>' + _.escape(item.get('ram') || '') + '</td>' %>
        <% htmlDiv = htmlDiv + '               </tr><tr>' %>
        <% htmlDiv = htmlDiv + '  <td><strong>Cpu</td></strong> '%>
        <% htmlDiv = htmlDiv + '<td>' +  _.escape(item.get('cpu') || '') + '</td>' %>
        <% htmlDiv = htmlDiv + '               </tr><tr>' %>
        <% htmlDiv = htmlDiv + '  <td><strong>Disk qty</td></strong> '%>
        <% htmlDiv = htmlDiv + '<td>' +  item.get('diskscount') || '' + ' </td>' %>
        <% htmlDiv = htmlDiv + '               </tr><tr>' %>
        <% htmlDiv = htmlDiv + ' <td><strong>Network int qty</td></strong>' %>
        <% htmlDiv = htmlDiv + '<td>' +  _.escape(item.get('netintcount') || '') + '</td>' %>                     
        <% htmlDiv = htmlDiv + ' </tr>' %>
        <% htmlDiv = htmlDiv + '  </thead> '%>
        <% htmlDiv = htmlDiv + ' </table> ' %>
        <%= htmlDiv %>
        <!-- delete button is is a separate form to prevent enter key from triggering a delete -->
        <form id="deleteProvisioningnotificationsButtonContainer" class="form-horizontal" onsubmit="return false;">
        <fieldset>
        <div class="control-group">
        <label class="control-label"></label>
        <div class="controls">
        <!-- <button id="deleteProvisioningnotificationsButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete <%= _.escape(item.get('notifid') || '') %></button> -->

        <span id="confirmDeleteProvisioningnotificationsContainer" class="hide">
        <button id="cancelDeleteProvisioningnotificationsButton" class="btn btn-mini">Cancel</button>
        <button id="confirmDeleteProvisioningnotificationsButton" class="btn btn-mini btn-danger">Confirm</button>

        </span>
        </div>
        </div>
        </fieldset>
        </form>
    </script>

    <!-- modal edit dialog -->
    <div class="modal hide fade" id="provisioningnotificationsDetailDialog">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>
            <h3>
                <span id="imgOs"></span> Details <span id="infos" class="text-primary"></span>
                <span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
            </h3>
        </div>
        <div class="modal-body ">
            <div id="modelAlert"></div>
            <div id="provisioningnotificationsModelContainer"></div>
            <div class="results"></div>
        </div>
        <div class="modal-footer">
            <!--<button class="btn" data-dismiss="modal" >Cancel</button>
            <button id="saveProvisioningnotificationsButton" class="btn btn-primary">Save Changes</button>-->
            <button class="btn btn-success btn-mini" id="excel" >Excel Export</button>
            <button class="btn btn-warning btn-mini" id="pdf" >PDF Export</button>
        </div> 
    </div>

    <div id="collectionAlert"></div>

    <div id="provisioningnotificationsCollectionContainer" class="collectionContainer">
    </div>


</div> <!-- /container -->

<?php
//$this->display('_Footer.tpl.php');
?>

</body>
</html>