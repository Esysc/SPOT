<?php
$this->assign('title', 'SPOT | Tree Builder');
$this->assign('nav', 'treebuilder');

$this->display('_Header.tpl.php');
?>
<link rel="stylesheet" href="scripts/jqtree/jqtree.css" />
<style>
    .right {

        position: absolute;
        top: 120px;
        right: -10px;
        height: 100%;
        width: 300px;
        margin-left: 1em;
        margin-right: 2em;

    }
    .results {
        display: none;
    }
</style>
<script src="scripts/jqtree/tree.jquery.js"></script>
<script src="scripts/jqtree/jquery.cookie.js"></script>
<script src="scripts/jqtree/alasql.min.js"></script> 
<script>
    $(document).ready(function () {
        $('#debug').html('Loading data, please wait .....   <img src="/SPOT/provisioning/images/loader.gif" />').show();
        $('#save').hide();
        $('#datatable').tree({
            autoOpen: true,
            dragAndDrop: true,
            saveState: true,
            autoScroll: true,
            onCanMoveTo: function (moved_node, target_node, position) {
                if (moved_node.parent_order_item_id.length == 0) {
                    // Nodes without BOM  configuration can be selected
                    // var new_parent 

                    return true;
                } else {

                    // Nodes with children cannot be selected
                    $('#error').html('Cannot move <strong>' + moved_node.name + '</strong> because it is  already part of a BOM ');
                    $('#error').show('slow');

                    setTimeout(function () {
                        $("#error").hide('slow')
                    }, 10000);
                    counter($('#error'), 10);
                    return false;
                }
            },
            onCreateLi: function (node, $li) {
                var $span = $li.children('.jqtree-element').find('span.jqtree-title');


                $span.addClass('badge');
            }

        });
        function counter($el, n) {
            var html = $el.html();
            (function loop() {

                $el.html(html + "<b class='pull-right badge badge-inverse'>" + n + "</b>");
                if (n--) {
                    setTimeout(loop, 1000);
                }
            })();
        }
        $('#details').on('click', function () {
            $('#basicModal').show();
        });
        $('#salesel').chosen({
            width: "100%"
        });


        $.ajax({
            url: "/SPOT/provisioning/includes/getOrdersSysproddb.php",
            type: "GET",
            async: false,
            cache: false,
            wait: true,
            success: function (jsonResult) {
                $('#debug').hide()
                var jdata = JSON.parse(jsonResult);

                $.each(jdata, function (i, o) {

                    $('#salesel').append('<option>' + o + '</option>').trigger("chosen:updated");
                });


            }
        });

        $('#salesel').on('change', function (e) {
            var salesorder = $("#salesel option:selected").val();
            $('#save').hide();
            //   console.log(salesorder);
            if (salesorder === '') {

                return;
            }
            $('#debug').html('').hide();
            $('#results').html('<center><img src="/SPOT/provisioning/images/loader.gif" /></center>');
            $('#results').show();
            $('.results').show();
            $("#datatable").html('');
            e.preventDefault();

            var SOarr = salesorder.split('|');
            var SO = SOarr[0].trim();
            // Get all data from DB x salesorder
            $.ajax({
                url: '/SPOT/provisioning/includes/treeBuilder.php?salesorder=' + SO,
                type: "GET",
                async: false,
                cache: false,
                wait: true,
                success: function (jsonResult) {
                    // console.log(jsonResult);
                    $('#results').hide();
                    $('.results').show();
                    try {
                        var Obj = $.parseJSON(jsonResult);
                    } catch (err) {

                        $('#error').html(jsonResult);
                        $('#error').show('slow');

                        setTimeout(function () {
                            $("#error").hide('slow')
                        }, 2000);
                        counter($('#error'), 2);

                        $("#salesel").val('').trigger("chosen:updated");

                    }
                    var map = {}, node, roots = [], nodes = [], parent;
                    $.each(Obj, function (i, linesObj) {
                        //Pront only items not already linked
                        var linked_to_order_item_id = $.trim(linesObj.linked_to_order_item_id);
                        var item_id = $.trim(linesObj.item_id);
                        // if (linked_to_order_item_id.length == 0 && item_id.length != 0) {
                        //   if (item_id.length != 0) {
                        var order_item_id = $.trim(linesObj.order_item_id);
                        var parent_order_item_id = $.trim(linesObj.parent_order_item_id);
                        var model_name = $.trim(linesObj.model_name);
                        var product_ref = $.trim(linesObj.product_ref);

                        linked_to_order_item_id.length == 0 ? parent = parent_order_item_id : parent = linked_to_order_item_id;

                        map = {
                            id: i,
                            order_item_id: order_item_id,
                            name: order_item_id,
                            item_id: item_id,
                            parent_order_item_id: parent_order_item_id,
                            linked_to_order_item_id: linked_to_order_item_id,
                            model_name: model_name,
                            product_ref: product_ref,
                            parent: parent,
                            label: order_item_id + " | " + model_name,
                            isChild: false,
                            children: []
                        }
                        nodes.push(map);


                        //    }

                    });
// create a name: node map
                    var dataMap = nodes.reduce(function (map, node) {
                        map[node.name] = node;
                        return map;
                    }, {});
                    //   console.log(dataMap);
/// create the tree array
                    var treeData = [];
                    nodes.forEach(function (node) {
                        // add to parent
                        var parent = dataMap[node.parent];
                        if (parent) {
                            // create child array if it doesn't exist
                            (parent.children || (parent.children = []))
                                    // add node to child array
                                    .push(node);
                        } else {
                            // parent is null or missing
                            treeData.push(node);
                        }
                    });
                    //   console.log(treeData);
                    $(function () {
                        var $tree = $('#datatable');

                        var control = false;
                        $tree.tree('loadData', treeData);

                        var selectednodes = $tree.tree('getSelectedNodes');
                        $('#tot').html('Number of nodes: ' + treeData.length)
                        $('#infos').html('Number of selected nodes: ' + selectednodes.length)
                        $.each(selectednodes, function (key, val) {
                            var $element = $(val.element);
                            var $title = $element.children('div').children('.jqtree-title');
                            $title.addClass('badge-success');
                        });
                        $tree.bind('tree.refresh', function () {
                            selectednodes = $tree.tree('getSelectedNodes');
                            $.each(selectednodes, function (key, val) {
                                var $element = $(val.element);
                                var $title = $element.children('div').children('.jqtree-title');
                                $title.addClass('badge-success');
                            });

                        });


                        $tree.on('tree.move', function (e) {

                            var move_infoObj = e.move_info
                            var moved_node = move_infoObj.moved_node;

                            var target_node = move_infoObj.target_node;
                            var position = move_infoObj.position;

                            var previous_parent = move_infoObj.previous_parent;

                            $('#success').html('<b>Moved </b> ' + moved_node.name +
                                    ' <b>' + position + '</b> ' + target_node.name +
                                    '<br /><b>Before was in : </b>' + previous_parent.name);
                            $('#success').show('slow');

                            setTimeout(function () {
                                $("#success").hide('slow')

                            }, 10000);
                            counter($('#success'), 10);

                            //console.log(e);
                            /* Every time you move a node, update the link_to_order_item_id value */


                        });
                        $tree.on(
                                'tree.click',
                                function (e) {
                                    // Disable single selection
                                    e.preventDefault();
                                    var selected_node = e.node;
                                    var selectednodes = $tree.tree('getSelectedNodes');
                                    var cancontinue = true;
                                    var $element = $(selected_node.element);
                                    var $title = $element.children('div').children('.jqtree-title');
                                    if (control) {
                                       
                                        if (selected_node.id == undefined) {
                                            
                                            cancontinue = false;
                                        }
                                        if ($tree.tree('isNodeSelected', selected_node)) {
                                            $tree.tree('removeFromSelection', selected_node);
                                            $title.removeClass('btn-primary');
                                            cancontinue = false;
                                        }
                                        if (cancontinue == true) {
                                            var this_parent = selected_node.parent;
                                            $.each(selectednodes, function (key, val) {
                                                if (val.parent !== this_parent) {
                                                    $('#error').html('Cannot select <strong>' + selected_node.name + ' </strong>because has a completely different parent ( not in the same tree hierarchy) ');
                                                    $('#error').show('slow');
                                                    setTimeout(function () {
                                                        $("#error").hide('slow')
                                                    }, 10000);
                                                    counter($('#error'), 10);
                                                    cancontinue = false;
                                                }
                                            });
                                            if (cancontinue == true) {
                                                $tree.tree('addToSelection', selected_node);
                                                $title.addClass('badge-success');
                                            }
                                        }
                                    } else {

                                        var nodes = $tree.tree('getSelectedNodes'),
                                                skip = false;
                                        for (var i = 0, l = nodes.length; i < l; i++) {
                                            var $element = $(nodes[i].element);
                                            var $title = $element.children('div').children('.jqtree-title');

                                            if (nodes[i] == selected_node) {
                                                skip = true;
                                                continue;
                                            }
                                            ;
                                            $tree.tree('removeFromSelection', nodes[i]);
                                            $title.removeClass('badge-success');
                                        }
                                        ;
                                        if (!skip) {
                                            $tree.tree('addToSelection', selected_node);
                                            var $element = $(selected_node.element);
                                            var $title = $element.children('div').children('.jqtree-title');

                                            $title.addClass('badge-success');
                                        }
                                        ;
                                    }
                                    selectednodes = $tree.tree('getSelectedNodes');
                                    $('#infos').html('Number of selected nodes: ' + selectednodes.length)
                                });
                        $('.res').on('click', function () {
                            selectednodes = $tree.tree('getSelectedNodes');
                            $.each(selectednodes, function (key, val) {
                                $tree.tree('removeFromSelection', val);
                                var $element = $(val.element);
                                var $title = $element.children('div').children('.jqtree-title');
                                $title.removeClass('badge-success');
                            });
                            selectednodes = $tree.tree('getSelectedNodes');
                            $('#infos').html('Number of selected nodes: ' + selectednodes.length)

                        });

                        $(document)
                                .bind('keydown', function (e) {
                                    if (e.which == 17) {
                                        control = true;
                                    }
                                    ;
                                })
                                .bind('keyup', function (e) {
                                    if (e.which == 17) {
                                        control = false;
                                    }
                                    ;
                                });


                        $tree.on('tree.move', function (event) {

                          /*  console.log('______________________________________');
                            console.log('moved_node', event.move_info.moved_node.name);
                            console.log('target_node', event.move_info.target_node.name);
                            console.log('position', event.move_info.position); */


                            var myNode = event.move_info.moved_node;
                            var target = event.move_info.target_node.order_item_id;
                            var myXDad = event.move_info.previous_parent.order_item_id;
                            var targetsDad = event.move_info.target_node.parent.order_item_id;
                            var linked_to_order_item_id;  // this is the variable to update in object dinamically
                            var isChild; // add this to filter later
                            if (event.move_info.position == 'after') {

                                if (target === myXDad) {
                                    if (typeof targetsDad === 'undefined') {
                                        // node has no dad, so it's a dad candidate
                                        var family = ('father=' + 0 + '&child=' + myNode);
                                      
                                        linked_to_order_item_id = '';
                                        isChild = false;
                                    } else {
                                        //get your target's dad as your dad
                                        var family = ('father=' + targetsDad + '&child=' + myNode);
                                       
                                        linked_to_order_item_id = targetsDad;
                                        isChild = true;
                                    }
                                } else if (!(myXDad === targetsDad)) {
                                    //get target's dad as your dad
                                    var family = ('father=' + 0 + '&child=' + myNode);
                                   
                                    linked_to_order_item_id = '';
                                    isChild = false;
                                }

                            }

                            if (event.move_info.position == 'inside') {
                                var family = ('father=' + target + '&child=' + event.move_info.moved_node.id)
                               
                                linked_to_order_item_id = target;
                                isChild = true;
                            }
                            //Finally set the new parent relationship
                            var selectednodes = $tree.tree('getSelectedNodes');
                            $.each(selectednodes, function (key, val) {
                                val.linked_to_order_item_id = linked_to_order_item_id;
                                val.isChild = isChild;
                                $tree.tree('moveNode', val, event.move_info.target_node, event.move_info.position);

                            });

                            //       myNode.linked_to_order_item_id = linked_to_order_item_id;
                            //     myNode.isChild = isChild;




                            var nodes = $tree.tree('toJson');

                            var jsonNodes = $.parseJSON(nodes);
                            //reload data from object
                            // allow the browser to redesign the tree after moving objects



                            var childs = [];

                            //childs = alasql('SELECT order_item_id,linked_to_order_item_id,product_ref  FROM ? \ WHERE isChild=true \ ', [jsonNodes]);
                            // get all children in new array
                            // Thanks to alasql 
                            childs = alasql('SEARCH / * WHERE(isChild=true)  FROM ?', [jsonNodes]);
                            // prepare to send to php script all datas
                            if (childs.length > 0)
                            {
                                $('#save').show();
                            } else
                            {
                                $('#save').hide();
                            }
                        });




                    });



                }





            });
        });
        $('.results').hide();
        $('#error').hide();
        $('#success').hide();
        $('#save').on('click', function () {
            var nodes = $('#datatable').tree('toJson');
            var jsonNodes = $.parseJSON(nodes);
            //  $('#debug').html(nodes);
            //  console.log(jsonNodes);
            var childs = [];

            //childs = alasql('SELECT order_item_id,linked_to_order_item_id,product_ref  FROM ? \ WHERE isChild=true \ ', [jsonNodes]);
            // get all children in new array
            // Thanks to alasql 
            childs = alasql('SEARCH / * WHERE(isChild=true)  FROM ?', [jsonNodes]);
            // prepare to send to php script all datas

            var salesorder = $("#salesel option:selected").val();
            var SOarr = salesorder.split('|');
            var SO = SOarr[0].trim();
            var url = '/SPOT/provisioning/includes/treeBuilder.php';
            $.each(childs, function (keys, value) {
                var post = {salesorder: SO};
                $.each(value, function (index, data) {
                    if (index === 'order_item_id' || index === 'linked_to_order_item_id' || index === 'product_ref') {
                        post[index] = data;
                    }

                });

             
                $.ajax({
                    url: url,
                    type: "POST",
                    data: post,
                    success: function (data) {
                        $('#debug').html('<button type="button" class="close" data-hide="alert" onclick="$(this).parent().hide();">&times;</button>\n\
                <b>Successfully posted changes to sysprodb. Please go to \n\
                    <a href="<?php echo URL_WEBSYSPRODDB; ?>' + SO + '" target="_blank">' + SO +
                                ' details</a> and check for changes having taken effect.\n\
<p class="alert alert-warning alert-block">Server sysproddb.my.compnay.com callback : ' + data + '</p>\n\
Please report any issue to @acs.</b>');
                        $('#debug').show('slow');


                        /*   setTimeout(function () {
                         $("#debug").hide('slow')
                         
                         }, 10000);
                         counter($('#debug'), 10);
                         */


                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        $('#error').html("An error occured: " + errorThrown);
                        $('#error').show();
                    }
                })
            });

        });


    });

</script>

<div class="container">

    <h1>
        <i class="icon-th-list"></i> Tree Builder


    </h1>

    <!-- underscore template for the collection -->

    <table class=" table-bordered table-responsive table table-striped">

        <tr class="salesel">
            <th >

                Select a stored SO

            </th>
        </tr>
        <tr class="salesel">
            <td>
                <select class="chosen" id="salesel" name="salesel" required autofocus="autofocus">
                    <option value="">
                        Select a sales order
                    </option>

                </select>
            </td>


        </tr>



    </table>


    <table class="table table-bordered table-striped table-responsive results">
        <tr>
            <th>
        <div class="row-fluid">
            <div class="span4">
                Tree Table 
            </div>
            <div class="span4">
                <span id="tot" class="badge badge-inverse"></span>
                <i class="icon-hand-down res"></i><span id="infos" class="badge badge-important res" title="Click to reset"></span>
            </div>
            <div class="span4">
                tip:  *CTRL-Click to multiselect
            </div>
        </div>
        </th>
        </tr>
        <tr>
            <td id="results">


            </td>
        </tr>
    </table>

    <div  id="datatable" class="block-style" >



    </div>
    <br />
    <p class="center-block"><button class="btn  btn-primary center" id="save">Save</button></p>
    <div class="right">
        <div id="error" class="alert alert-error" style="display:none"></div>
        <div id="success" class="alert alert-succes" style="display:none"></div>
        <div id="debug" class="alert alert-success"  hidden="hidden">



        </div>
    </div>

</div> <!-- /container -->

<?php
$this->display('_Footer.tpl.php');
?>


