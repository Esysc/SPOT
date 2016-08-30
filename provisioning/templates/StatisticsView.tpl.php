<?php
$this->assign('title', 'SPOT | Statistics');
$this->assign('nav', '');

$this->display('_Header.tpl.php');
$baseYear = 2015;
$currentYear = date('Y');
$diff = $currentYear - $baseYear;
$options = '<select class="chosen" id="year"><option value="">Select a year</option>';

for ($x = 0; $x <= $diff; $x++) {

    $year = $baseYear + $x;

    $options .= "<option  value='$year'>$year</option>";
}
$options .= "</select>";
?>

<script>
    $('document').ready(function () {

        $('#year').chosen();
        $('#year').on('change', function () {


            window.location.href = '/SPOT/provisioning/stats/' + $(this).val();


        });
        // This fucntion is for page number 1 (Operating system distribution)
        // at the end, in pagination function, I added the condition to hide one of the div at the beginning
        $('div').delegate('#osdistrib1', 'click', function (e) {
            var target = e.target.toString();
            // Avoid toggling if export is clicked
            if ( target.indexOf("LI") == -1) {

                $('#c1').hide('fast');
                $('#c2').show('slow');
            }
        });
        $('div').delegate('#osdistrib2', 'click', function (e) {
            var target = e.target.toString();
            // Avoid toggling if export is clicked
            if ( target.indexOf("LI") == -1)
            {
                $('#c2').hide('fast');
                $('#c1').show('slow');
            }
        });
        // This fucntion is for page number 6 (Timezones statistics)
        // at the end, in pagination function, I added the condition to hide one of the div at the beginning
        $('div').delegate('#timezones1', 'click', function (e) {
            var target = e.target.toString();
            console.log(target);
            // Avoid toggling if export is clicked
            if ( target.indexOf("LI") == -1)
            {
                $('#c7').hide('fast');
                $('#c7bis').show('slow');
            }
        });
        $('div').delegate('#timezones2', 'click', function (e) {
            var target = e.target.toString();
            // Avoid toggling if export is clicked
            if ( target.indexOf("LI") == -1)
            {
                $('#c7bis').hide('fast');
                $('#c7').show('slow');
            }
        });

    });</script>
<style>
    /* white color data labels */
    .jqplot-point-label{color:white;}
    /* white color data labels */
    .jqplot-data-label{color:white;}


</style>

<div class="container">

    <h1>
        <i class="icon-th-list"></i>  <?php echo $this->title; ?>
<!--    <span id=loader class="loader progress progress-striped active"><span class="bar"></span></span> -->

    </h1>

    <!-- underscore template for the collection -->
    <h2 class="alert alert-info" role="alert"><i class="icon-cloud"></i> SPOT Charts <?php echo $this->viewYear; ?><span class="pull-right">
            <?php echo $options; ?>

        </span></h2>
    <!-- underscore template for the collection -->

    <?php if ($this->pie) { ?>

        <div id="content">




            <div class="items">


                <?php echo $this->months; ?> 

            </div>

            <div class="items">
                <?php
                echo $this->titleosdistrib;
                ?>
                <div id="osdistrib1">
                    <?php echo $this->bar; ?> 
                </div> 
                <?php $temp1 = $this->pie; ?>

                <div id='osdistrib2'>

                    <?php echo $temp1; ?> 
                </div>

            </div>

            <div class="items">



                <?php
                echo $this->stacked;
                ?> 


            </div>
            <div class="items">



                <?php
                echo $this->users;
                ?> 


            </div>
            <div class="items">



                <?php
                echo $this->models;
                ?> 
            </div>

            <div class="items" >



                <?php
                echo $this->titletimezones;
                echo '<div id="timezones1" >';
                echo $this->timezones1;
                echo "</div>";
                $temp2 = $this->timezones2;
                echo "<div id='timezones2'>";
                echo $temp2;
                echo "</div>";
                ?> 
            </div>

        </div>

    <?php } else { ?>
        <h3 class="alert alert-error" role="alert"><center> No data found for <?php echo $this->viewYear; ?></center></h3>
    <?php } ?>

    <div class="pagination pagination-centered " id="pagingControls"></div>





</div> <!-- /container -->



<script>
    var Imtech = {};
    Imtech.Pager = function () {
        this.paragraphsPerPage = 1;
        this.currentPage = 1;
        this.pagingControlsContainer = '#pagingControls';
        this.pagingContainerPath = '#content';
        this.numPages = function () {
            var numPages = 0;
            if (this.paragraphs != null && this.paragraphsPerPage != null) {
                numPages = Math.ceil(this.paragraphs.length / this.paragraphsPerPage);
            }

            return numPages;
        };
        this.showPage = function (page) {

            this.currentPage = page;
            var html = '';
            this.paragraphs.slice((page - 1) * this.paragraphsPerPage,
                    ((page - 1) * this.paragraphsPerPage) + this.paragraphsPerPage).each(function () {
                html += '<div class="items">' + $(this).html() + '</div>';
            });
            $(this.pagingContainerPath).fadeOut(1);
            console.log(this.pagingContainerPath);
            $(this.pagingContainerPath).html(html).fadeIn('slow');
            renderControls(this.pagingControlsContainer, this.currentPage, this.numPages());
        }

        var renderControls = function (container, currentPage, numPages) {
            var pagingControls = '<ul class="sele">';
            for (var i = 1; i <= numPages; i++) {
                switch (i) {
                    case 2:
                        $('#c2').hide(); // This page (number 2) contains two graphs, so we hide one and on click we show alternatively

                        break;
                    case 6:
                        $('#c7bis').hide(); // This page (number 6) contains two graphs, so we hide one and on click we show alternatively
                        break;
                }

                if (i != currentPage) {


                    pagingControls += '<li><a href="#" onclick="pager.showPage(' + i + '); return false;">' + i + '</a></li>';
                } else {
                    pagingControls += '<li class="active"><a href="#" onclick="return false;">' + i + '</a></li>';
                }

            }

            pagingControls += '</ul>';
            $(container).html(pagingControls);
        }
    }

    var pager = new Imtech.Pager();
    $(document).ready(function () {
        pager.paragraphsPerPage = 1; // set amount elements per page
        pager.pagingContainer = $('#content'); // set of main container
        pager.paragraphs = $('div.items', pager.pagingContainer); // set of required containers
        pager.showPage(1);
    });


</script>
<?php
$this->display('_Footer.tpl.php');
?>
