/**
 * View logic for Adresses
 */

/**
 * application logic specific to the Customer_Ip_Inventory listing page
 */
var page = {
    adresses: new model.Customer_Ip_InventoryCollection(),
    collectionView: null,
    customer_Ip_Inventory: null,
    modelView: null,
    isInitialized: false,
    isInitializing: false,
    fetchParams: {filter: '', orderBy: '', orderDesc: '', page: 1},
    fetchInProgress: false,
    dialogIsOpen: false,
    /**
     *
     */
    init: function () {
        // ensure initialization only occurs once
        if (page.isInitialized || page.isInitializing)
            return;
        page.isInitializing = true;

        if (!$.isReady && console)
            console.warn('page was initialized before dom is ready.  views may not render properly.');

        // make the new button clickable
        $("#newCustomer_Ip_InventoryButton").click(function (e) {
            e.preventDefault();
            page.showDetailDialog();
        });

        // let the page know when the dialog is open
        $('#customer_Ip_InventoryDetailDialog').on('show', function () {
            page.dialogIsOpen = true;
        });


        // when the model dialog is closed, let page know and reset the model view
        $('#customer_Ip_InventoryDetailDialog').on('hidden', function () {
            $('#modelAlert').html('');
            page.dialogIsOpen = false;
        });

        // save the model when the save button is clicked
        $("#saveCustomer_Ip_InventoryButton").click(function (e) {
            e.preventDefault();
            page.updateModel();
        });

        // initialize the collection view
        this.collectionView = new view.CollectionView({
            el: $("#customer_Ip_InventoryCollectionContainer"),
            templateEl: $("#customer_Ip_InventoryCollectionTemplate"),
            collection: page.adresses
        });

        // initialize the search filter
        $('#filter').change(function (obj) {
            page.fetchParams.filter = $('#filter').val();
            page.fetchParams.page = 1;
            page.fetchAdresses(page.fetchParams);
        });


        // make the rows clickable ('rendered' is a custom event, not a standard backbone event)
        this.collectionView.on('rendered', function () {

            // attach click handler to the table rows for editing
            $('table.collection tbody tr').click(function (e) {
                e.preventDefault();
                var m = page.adresses.get(this.id);
                page.showDetailDialog(m);
            });

            // make the headers clickable for sorting
            $('table.collection thead tr th').click(function (e) {
                e.preventDefault();
                var prop = this.id.replace('header_', '');

                // toggle the ascending/descending before we change the sort prop
                page.fetchParams.orderDesc = (prop == page.fetchParams.orderBy && !page.fetchParams.orderDesc) ? '1' : '';
                page.fetchParams.orderBy = prop;
                page.fetchParams.page = 1;
                page.fetchAdresses(page.fetchParams);
            });

            // attach click handlers to the pagination controls
            $('.pageButton').click(function (e) {
                e.preventDefault();
                page.fetchParams.page = this.id.substr(5);
                page.fetchAdresses(page.fetchParams);
            });






            page.isInitialized = true;
            page.isInitializing = false;
        });

        // backbone docs recommend bootstrapping data on initial page load, but we live by our own rules!
        this.fetchAdresses({page: 1});

        // initialize the model view
        this.modelView = new view.ModelView({
            el: $("#customer_Ip_InventoryModelContainer")
        });

        // tell the model view where it's template is located
        this.modelView.templateEl = $("#customer_Ip_InventoryModelTemplate");

        if (model.longPollDuration > 0) {
            setInterval(function () {

                if (!page.dialogIsOpen) {
                    page.fetchAdresses(page.fetchParams, true);
                }

            }, model.longPollDuration);
        }
    },
    /**
     * Fetch the collection data from the server
     * @param object params passed through to collection.fetch
     * @param bool true to hide the loading animation
     */
    fetchAdresses: function (params, hideLoader) {
        // persist the params so that paging/sorting/filtering will play together nicely
        page.fetchParams = params;

        if (page.fetchInProgress) {
            if (console)
                console.log('supressing fetch because it is already in progress');
        }

        page.fetchInProgress = true;

        if (!hideLoader)
            app.showProgress('loader');

        page.adresses.fetch({
            data: params,
            success: function () {

                if (page.adresses.collectionHasChanged) {
                    // TODO: add any logic necessary if the collection has changed
                    // the sync event will trigger the view to re-render
                }

                app.hideProgress('loader');
                page.fetchInProgress = false;
            },
            error: function (m, r) {
                app.appendAlert(app.getErrorMessage(r), 'alert-error', 0, 'collectionAlert');
                app.hideProgress('loader');
                page.fetchInProgress = false;
            }

        });
    },
    /**
     * show the dialog for editing a model
     * @param model
     */
    showDetailDialog: function (m) {
        var status = "";
        //test m , if undefined we add a new record here
        if (typeof m === 'undefined') {
            status = "active";
        }
        else
        {
            status = m.attributes.status; // Get the status of the object, if active continue tu edit
        }
        if (status === "active") {
            // show the modal dialog
            $('#customer_Ip_InventoryDetailDialog').modal({show: true});

            // if a model was specified then that means a user is editing an existing record
            // if not, then the user is creating a new record
            page.customer_Ip_Inventory = m ? m : new model.Customer_Ip_InventoryModel();

            page.modelView.model = page.customer_Ip_Inventory;

            if (page.customer_Ip_Inventory.id == null || page.customer_Ip_Inventory.id == '') {
                // this is a new record, there is no need to contact the server
                page.renderModelView(false);
            } else {
                app.showProgress('modelLoader');

                // fetch the model from the server so we are not updating stale data
                page.customer_Ip_Inventory.fetch({
                    success: function () {
                        // data returned from the server.  render the model view
                        page.renderModelView(true);
                    },
                    error: function (m, r) {
                        app.appendAlert(app.getErrorMessage(r), 'alert-error', 0, 'modelAlert');
                        app.hideProgress('modelLoader');
                    }

                });
            }
        }
        else
        {
            $('#servermsg').html('You can\'t edit a record which status is not active. You can only add a new one.');
            $('#basicModal').modal();
            return false;
        }

    },
    /**
     * Render the model template in the popup
     * @param bool show the delete button
     */
    renderModelView: function (showDeleteButton) {
        page.modelView.render();
        $(document).ready(function () {
            $('textarea#comments').summernote();
            $('textarea#systemName').summernote();
        });

        app.hideProgress('modelLoader');

        // initialize any special controls
        try {
            $('.date-picker')
                    .datepicker()
                    .on('changeDate', function (ev) {
                        $('.date-picker').datepicker('hide');
                    });
        } catch (error) {
            // this happens if the datepicker input.value isn't a valid date
            if (console)
                console.log('datepicker error: ' + error.message);
        }

        $('.timepicker-default').timepicker({defaultTime: 'value'});


        if (showDeleteButton) {
            // attach click handlers to the delete buttons

            $('#deleteCustomer_Ip_InventoryButton').click(function (e) {
                e.preventDefault();
                $('#confirmDeleteCustomer_Ip_InventoryContainer').show('fast');
            });

            $('#cancelDeleteCustomer_Ip_InventoryButton').click(function (e) {
                e.preventDefault();
                $('#confirmDeleteCustomer_Ip_InventoryContainer').hide('fast');
            });

            $('#confirmDeleteCustomer_Ip_InventoryButton').click(function (e) {
                e.preventDefault();
                page.deleteModel();
            });

        } else {
            // no point in initializing the click handlers if we don't show the button
            $('#deleteCustomer_Ip_InventoryButtonContainer').hide();
        }



        $('.ipaddress').each(function () {
            $(this).change(function () {
                var ip = $(this).val();
                var error = "Only valid IP address, for example 192.168.1.0 or 192.168.1.255. ";
                if (ip.length > 0 && ip.length <= 15) {



                    $(this).attr('title', 'The IP address is correct');
                    $(this).css('background', '#82FA58');
                    var ipSlot = ip.split(".");
                    if ((ipSlot[3] != 0) && (ipSlot[3] != 255)) {


                        $(this).val('');
                        $(this).attr('title', error);
                        $(this).css('background', 'pink');

                        return false;
                    }
                    if (ipSlot.length == 4) {
                        for (var i = 0; i < ipSlot.length; i++) {
                            var l = ipSlot[i].length;
                            console.log(ipSlot);
                            if (l > 0 && l <= 3) {
                                if (ipSlot[i] >= 0 && ipSlot[i] <= 255) {






                                }
                                else {
                                    $(this).val('');
                                    $(this).attr('title', error);
                                    $(this).css('background', 'pink');

                                    return false;
                                }
                            } else {
                                $(this).val('');
                                $(this).attr('title', error);
                                $(this).css('background', 'pink');

                                return false;
                            }

                        }
                    } else {
                        $(this).val('');
                        $(this).attr('title', error);
                        $(this).css('background', 'pink');

                        return false;
                    }

                }
                else {
                    $(this).val('');
                    $(this).attr('title', error);

                    $(this).css('background', 'pink');
                    return false;
                }




            });
        });


        $("#account").keyup(function ()
        {
            var searchid = $(this).val();
            var dataString = 'acr=' + searchid;
            if (searchid != '')
            {
                $.ajax({
                    type: "POST",
                    url: "includes/findCustomer.php",
                    data: dataString,
                    cache: false,
                    success: function (html)
                    {
                        $("#result").html(html).show();
                    }
                });
            }
            return false;
        });

        $("#location").keyup(function ()
        {
            var searchid = $(this).val();
            var dataString = 'location=' + searchid;
            if (searchid != '')
            {
                $.ajax({
                    type: "POST",
                    url: "includes/findLocation.php",
                    data: dataString,
                    cache: false,
                    success: function (html)
                    {
                        $("#result2").html(html).show();
                    }
                });
            }
            return false;
        });

        $("#result").bind("click", function (e) {
            var $clicked = $(e.target);
            var $name = $clicked.find('.name').html();
            var decoded = $("#result").html($name).text();
            $('#account').val(decoded);
        });
        $(document).bind("click", function (e) {
            var $clicked = $(e.target);
            if (!$clicked.hasClass("search")) {
                $("#result").fadeOut();
            }
        });
        $('#account').click(function () {
            $("#result").fadeIn();
        });

        $("#result2").bind("click", function (e) {
            var $clicked = $(e.target);
            var $name = $clicked.find('.name').html();
            var decoded = $("#result2").html($name).text();
            $('#location').val(decoded);
        });
        $(document).bind("click", function (e) {
            var $clicked = $(e.target);
            if (!$clicked.hasClass("search")) {
                $("#result2").fadeOut();
            }
        });

        $('#location').click(function () {
            $("#resulti2").fadeIn();
        });




    },
    /**
     * update the model that is currently displayed in the dialog
     */
    updateModel: function () {

        // reset any previous error

        $('#modelAlert').html('');
        $('.control-group').removeClass('error');
        $('.help-inline').html('');
        if ($('input#subnet').val() === '' ||
                $('select#netmask').val() === '' ||
                $('input#account').val() === '' ||
                $('input#location').val() === '' ||
                $('select#validatedBy').val() === ''
                ) {

            $('#modelAlert').html('<p>Some mandatory fields missing...</p>');
            $('.required').addClass('error');
            $('.help-inline').html('Please fill the empties fields');
            return false;
        }
        else
        {

            // if this is new then on success we need to add it to the collection
            var isNew = page.customer_Ip_Inventory.isNew();

            app.showProgress('modelLoader');

            page.customer_Ip_Inventory.save({
                'subnet': $('input#subnet').val(),
                'netmask': $('select#netmask').val(),
                'account': $('input#account').val(),
                'location': $('input#location').val(),
                'systemName': $('textarea#systemName').code(),
                'entt': $('input#entt').val(),
                'remoteAccess': $('input#remoteAccess').val(),
                'comments': $('textarea#comments').code(),
                'valdate': $('input#valdate').val(),
                'validatedBy': $('select#validatedBy').val(),
                'lsmod': $('input#lsmod').val() + ' ' + $('input#lsmod-time').val(),
                'status': $('select#status').val()
            }, {
                wait: true,
                success: function () {

                    $('#customer_Ip_InventoryDetailDialog').modal('hide');
                    setTimeout("app.appendAlert('Customer_Ip_Inventory was sucessfully " + (isNew ? "inserted" : "updated") + "','alert-success',3000,'collectionAlert')", 500);
                    app.hideProgress('modelLoader');

                    // if the collection was initally new then we need to add it to the collection now
                    if (isNew) {
                        page.adresses.add(page.customer_Ip_Inventory)
                    }

                    if (model.reloadCollectionOnModelUpdate) {
                        // re-fetch and render the collection after the model has been updated
                        page.fetchAdresses(page.fetchParams, true);
                    }

                },
                error: function (model, response, scope) {

                    app.hideProgress('modelLoader');

                    app.appendAlert(app.getErrorMessage(response), 'alert-error', 0, 'modelAlert');

                    try {
                        var json = $.parseJSON(response.responseText);

                        if (json.errors) {
                            $.each(json.errors, function (key, value) {
                                $('#' + key + 'InputContainer').addClass('error');
                                $('#' + key + 'InputContainer span.help-inline').html(value);
                                $('#' + key + 'InputContainer span.help-inline').show();
                            });
                        }
                    } catch (e2) {
                        if (console)
                            console.log('error parsing server response: ' + e2.message);
                    }
                }

            });
        }
    },
    /**
     * delete the model that is currently displayed in the dialog
     */
    deleteModel: function () {
        // reset any previous errors
        $('#modelAlert').html('');

        app.showProgress('modelLoader');

        page.customer_Ip_Inventory.destroy({
            wait: true,
            success: function () {
                $('#customer_Ip_InventoryDetailDialog').modal('hide');
                setTimeout("app.appendAlert('The Customer_Ip_Inventory record was deleted','alert-success',3000,'collectionAlert')", 500);
                app.hideProgress('modelLoader');

                if (model.reloadCollectionOnModelUpdate) {
                    // re-fetch and render the collection after the model has been updated
                    page.fetchAdresses(page.fetchParams, true);
                }
            },
            error: function (model, response, scope) {
                app.appendAlert(app.getErrorMessage(response), 'alert-error', 0, 'modelAlert');
                app.hideProgress('modelLoader');
            }
        });
    }
};

$('#synch')
        .datepicker()
        .on('changeDate', function (ev) {
            $('#synch').datepicker('hide');
        });
