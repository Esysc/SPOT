/**
 * View logic for Tblprogresses
 */

/**
 * application logic specific to the Tblprogress listing page
 */
var page = {
    tblprogresses: new model.TblprogressCollection(),
    collectionView: null,
    tblprogress: null,
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
        $("#newTblprogressButton").click(function (e) {
            e.preventDefault();
            page.showDetailDialog();
        });

        // let the page know when the dialog is open
        $('#tblprogressDetailDialog').on('show', function () {
            page.dialogIsOpen = true;
        });

        // when the model dialog is closed, let page know and reset the model view
        $('#tblprogressDetailDialog').on('hidden', function () {
            $('#modelAlert').html('');
            page.dialogIsOpen = false;
        });

        // save the model when the save button is clicked
        $("#saveTblprogressButton").click(function (e) {
            e.preventDefault();
            page.updateModel();
        });

        // initialize the collection view
        this.collectionView = new view.CollectionView({
            el: $("#tblprogressCollectionContainer"),
            templateEl: $("#tblprogressCollectionTemplate"),
            collection: page.tblprogresses
        });

        // initialize the search filter
        $('#filter').change(function (obj) {
            page.fetchParams.filter = $('#filter').val();
            page.fetchParams.page = 1;
            page.fetchTblprogresses(page.fetchParams);
        });




        // make the rows clickable ('rendered' is a custom event, not a standard backbone event)
        this.collectionView.on('rendered', function () {

            // attach click handler to the table rows for editing
            $('table.collection tbody tr').click(function (e) {
                e.preventDefault();
                var m = page.tblprogresses.get(this.id);
                page.chooseAndSetOrder(m.attributes);

                //   page.showDetailDialog(m);
            });


            // make the headers clickable for sorting
            $('table.collection thead tr th').click(function (e) {
                e.preventDefault();
                var prop = this.id.replace('header_', '');

                // toggle the ascending/descending before we change the sort prop
                page.fetchParams.orderDesc = (prop == page.fetchParams.orderBy && !page.fetchParams.orderDesc) ? '1' : '';
                page.fetchParams.orderBy = prop;
                page.fetchParams.page = 1;
                page.fetchTblprogresses(page.fetchParams);
            });

            // attach click handlers to the pagination controls
            $('.pageButton').click(function (e) {
                e.preventDefault();
                page.fetchParams.page = this.id.substr(5);
                page.fetchTblprogresses(page.fetchParams);
            });

            $(document).ready(function () {
                $('[data-toggle=tooltip]').tooltip();
            });

            page.isInitialized = true;
            page.isInitializing = false;
        });

        // backbone docs recommend bootstrapping data on initial page load, but we live by our own rules!
        this.fetchTblprogresses({page: 1});

        // initialize the model view
        this.modelView = new view.ModelView({
            el: $("#tblprogressModelContainer")
        });

        // tell the model view where it's template is located
        this.modelView.templateEl = $("#tblprogressModelTemplate");

        if (model.longPollDuration > 0) {
            setInterval(function () {

                if (!page.dialogIsOpen) {
                    page.fetchTblprogresses(page.fetchParams, true);

                }

            }, model.longPollDuration);
        }
    },
    /**
     * Fetch the collection data from the server
     * @param object params passed through to collection.fetch
     * @param bool true to hide the loading animation
     */
    fetchTblprogresses: function (params, hideLoader) {
        // persist the params so that paging/sorting/filtering will play together nicely
        page.fetchParams = params;

        if (page.fetchInProgress) {
            if (console)
                console.log('supressing fetch because it is already in progress');
        }

        page.fetchInProgress = true;

        if (!hideLoader)
            app.showProgress('loader');

        page.tblprogresses.fetch({
            data: params,
            success: function () {

                // page.filterProgress();

                if (page.tblprogresses.collectionHasChanged) {
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
        var currentdate = new Date();
        var datetime = currentdate.getDate() + "/"
                + (currentdate.getMonth() + 1) + "/"
                + currentdate.getFullYear() + " @ "
                + currentdate.getHours() + ":"
                + currentdate.getMinutes() + ":"
                + currentdate.getSeconds();


        // show the modal dialog
        $('#tblprogressDetailDialog').modal({show: true});


        // if a model was specified then that means a user is editing an existing record
        // if not, then the user is creating a new record
        page.tblprogress = m ? m : new model.TblprogressModel();

        page.modelView.model = page.tblprogress;

        if (page.tblprogress.id == null || page.tblprogress.id == '') {

            // this is a new record, there is no need to contact the server
            page.renderModelView(false);
            $('#creationdate').val(datetime);
        } else {
            app.showProgress('modelLoader');

            // fetch the model from the server so we are not updating stale data
            page.tblprogress.fetch({
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

    },
    filterProgress: function () {


        /*
         * custom code to filter records based on completed param in data table
         * Only the records that are NOT completely provisioned are showed. this hacks permits the utilisation of the same 
         * DB table (tblprogress) for both in progress and completed records.
         */
        var collection = page.tblprogresses;
        var json = JSON.parse(page.tblprogresses.lastResponseText);
        var jrecords = json.rows;
        var ret = new Array();

        var models = new Array;
        models.push(collection.models);
        var records = 0;

        for (var i = 0; i < jrecords.length; i++) {

            var completed = JSON.parse(jrecords[i].data).completed;
            if (typeof completed === 'undefined') {
                ret[records] = jrecords[i];
                //  model[records] = collection.model[i];
                models[records] = collection.models[i];
                records++;

            }
        }

        var newArray = new Array();
        for (var i = 0; i < ret.length; i++) {
            if (ret[i]) {

                newArray.push(ret[i]);
            }
        }
        ret = newArray;


        var newArray = new Array();
        for (var i = 0; i < models.length; i++) {
            if (models[i]) {

                newArray.push(models[i]);
            }
        }
        models = newArray;

        var totalPages = parseInt(records / 10 + 1);
        var jret = JSON.stringify(ret);
        collection.lastResponseText = jret;
        collection.models = models;
        //collection.model = model;
        collection.totalResults = records;
        collection.totalPages = totalPages;

        page.tblprogresses = collection;
        console.log(page.tblprogresses);

    },
    /**
     * 
     * @param {type} attr (send sales order as session var
     * @returns {undefined}
     */

    chooseAndSetOrder: function (attr) {
        $('#modelAlert').html('');
        $('.control-group').removeClass('error');
        $('.help-inline').html('');



        //   $.put("api/tblprogress", JSONdata, function( data ) {
        $.ajax({
            url: "includes/loadSession.php",
            type: "POST",
            data: {salesorder: attr.salesorder,
                data: attr.data
            },
            wait: true,
            success: function () {
                // $('#success').html('Successfully selected sales order  ' + attr.salesorder + '  for provisioning. You can now proceed to provision the machines:  <a href=\"./provisioning1\" class=\"btn btn-large btn-primary\">Wizard');
                // $('#failed').hide();
                // $('#success').show();
                $('#servermsg').html('Successfully selected sales order <strong>' + attr.salesorder + ' </strong>for provisioning. You can now proceed to provision the machines:  <a href=\"./provisioning1\" class=\"btn btn-info btn-primary\">Wizard');
                $('#basicModal').modal();
            },
            error: function (model, response, scope) {

                //$('#failed').html('An error occured,The order was not loaded successfully. The error reported is: ' + scope);
                //$('#success').hide();
                //$('#failed').show();
                $('#servermsg').html('An error occured loading the order <strong> ' + $('input#salesorder').val() + '</string>.. The error reported is: ' + scope);
                $('#basicModal').modal();
            }

        });
    },
    /**
     * Render the model template in the popup
     * @param bool show the delete button
     */
    renderModelView: function (showDeleteButton) {


        page.modelView.render();

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

            $('#deleteTblprogressButton').click(function (e) {
                e.preventDefault();
                $('#confirmDeleteTblprogressContainer').show('fast');
            });

            $('#cancelDeleteTblprogressButton').click(function (e) {
                e.preventDefault();
                $('#confirmDeleteTblprogressContainer').hide('fast');
            });

            $('#confirmDeleteTblprogressButton').click(function (e) {
                e.preventDefault();
                page.deleteModel();
            });

        } else {
            // no point in initializing the click handlers if we don't show the button
            $('#deleteTblprogressButtonContainer').hide();
        }
    },
    /**
     * update the model that is currently displayed in the dialog
     */
    updateModel: function () {
        // reset any previous errors
        $('#modelAlert').html('');
        $('.control-group').removeClass('error');
        $('.help-inline').html('');

        // if this is new then on success we need to add it to the collection
        var isNew = page.tblprogress.isNew();

        app.showProgress('modelLoader');
        var acr = $('input#customeracr').val();
        var release = $('input#release').val();
        if (acr != '') {
            var value = acr.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
            var intRegex = /^\d+$/;
            if (intRegex.test(value)) {
                app.hideProgress('modelLoader');
                var errors = "Field must be a string";
                $('customeracrInputContainer').addClass('error');
                $('#customeracrInputContainer span.help-inline').html(errors);
                $('#customeracrInputContainer span.help-inline').show();
                return false;
            }
        } else {
            app.hideProgress('modelLoader');
            var errors = "Customer ACR is required";
            $('#customeracrInputContainer').addClass('error');
            $('#customeracrInputContainer span.help-inline').html(errors);
            $('#customeracrInputContainer span.help-inline').show();
            return false;
        }
        var string = '{"CustomerACR":"' + acr + '","releasename":"' + release + '"}';
        $('textarea#data').val(string);
        page.tblprogress.save({
            'user': $('input#user').val(),
            'data': $('textarea#data').val(),
            'salesorder': $('input#salesorder').val(),
            'creationdate': $('input#creationdate').val()
        }, {
            wait: true,
            success: function () {
                $('#tblprogressDetailDialog').modal('hide');
                setTimeout("app.appendAlert('Tblprogress was sucessfully " + (isNew ? "inserted" : "updated") + "','alert-success',3000,'collectionAlert')", 500);
                app.hideProgress('modelLoader');

                // if the collection was initally new then we need to add it to the collection now
                if (isNew) {
                    page.tblprogresses.add(page.tblprogress)
                }

                if (model.reloadCollectionOnModelUpdate) {
                    // re-fetch and render the collection after the model has been updated
                    page.fetchTblprogresses(page.fetchParams, true);
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
    },
    /**
     * delete the model that is currently displayed in the dialog
     */
    deleteModel: function () {
        // reset any previous errors
        $('#modelAlert').html('');

        app.showProgress('modelLoader');

        page.tblprogress.destroy({
            wait: true,
            success: function () {
                $('#tblprogressDetailDialog').modal('hide');
                setTimeout("app.appendAlert('The Tblprogress record was deleted','alert-success',3000,'collectionAlert')", 500);
                app.hideProgress('modelLoader');

                if (model.reloadCollectionOnModelUpdate) {
                    // re-fetch and render the collection after the model has been updated
                    page.fetchTblprogresses(page.fetchParams, true);
                }
            },
            error: function (model, response, scope) {
                app.appendAlert(app.getErrorMessage(response), 'alert-error', 0, 'modelAlert');
                app.hideProgress('modelLoader');
            }
        });
    }

};


