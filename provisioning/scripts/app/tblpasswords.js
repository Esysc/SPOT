/**
 * View logic for TblPasswords
 */

/**
 * application logic specific to the TblPassword listing page
 */
var page = {
    tblPasswords: new model.TblPasswordCollection(),
    collectionView: null,
    tblPassword: null,
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
        $("#newTblPasswordButton").click(function (e) {
            e.preventDefault();
            page.showDetailDialog();
        });

        // let the page know when the dialog is open
        $('#tblPasswordDetailDialog').on('show', function () {
            page.dialogIsOpen = true;
        });

        // when the model dialog is closed, let page know and reset the model view
        $('#tblPasswordDetailDialog').on('hidden', function () {
            $('#modelAlert').html('');
            page.dialogIsOpen = false;
        });

        // save the model when the save button is clicked
        $("#saveTblPasswordButton").click(function (e) {
            e.preventDefault();
            page.updateModel();
        });

        // initialize the collection view
        this.collectionView = new view.CollectionView({
            el: $("#tblPasswordCollectionContainer"),
            templateEl: $("#tblPasswordCollectionTemplate"),
            collection: page.tblPasswords
        });

        // initialize the search filter
        $('#filter').change(function (obj) {
            page.fetchParams.filter = $('#filter').val();
            page.fetchParams.page = 1;
            page.fetchTblPasswords(page.fetchParams);
        });

        // make the rows clickable ('rendered' is a custom event, not a standard backbone event)
        this.collectionView.on('rendered', function () {

            // attach click handler to the table rows for editing
            $('table.collection tbody tr').click(function (e) {
                e.preventDefault();
                var m = page.tblPasswords.get(this.id);
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
                page.fetchTblPasswords(page.fetchParams);
            });

            // attach click handlers to the pagination controls
            $('.pageButton').click(function (e) {
                e.preventDefault();
                page.fetchParams.page = this.id.substr(5);
                page.fetchTblPasswords(page.fetchParams);
            });

            page.isInitialized = true;
            page.isInitializing = false;
        });

        // backbone docs recommend bootstrapping data on initial page load, but we live by our own rules!
        this.fetchTblPasswords({page: 1});

        // initialize the model view
        this.modelView = new view.ModelView({
            el: $("#tblPasswordModelContainer")
        });

        // tell the model view where it's template is located
        this.modelView.templateEl = $("#tblPasswordModelTemplate");

        if (model.longPollDuration > 0) {
            setInterval(function () {

                if (!page.dialogIsOpen) {
                    page.fetchTblPasswords(page.fetchParams, true);
                }

            }, model.longPollDuration);
        }
    },
    /**
     * Fetch the collection data from the server
     * @param object params passed through to collection.fetch
     * @param bool true to hide the loading animation
     */
    fetchTblPasswords: function (params, hideLoader) {
        // persist the params so that paging/sorting/filtering will play together nicely
        page.fetchParams = params;

        if (page.fetchInProgress) {
            if (console)
                console.log('supressing fetch because it is already in progress');
        }

        page.fetchInProgress = true;

        if (!hideLoader)
            app.showProgress('loader');

        page.tblPasswords.fetch({
            data: params,
            success: function () {

                if (page.tblPasswords.collectionHasChanged) {
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

        // show the modal dialog
        $('#tblPasswordDetailDialog').modal({show: true});

        // if a model was specified then that means a user is editing an existing record
        // if not, then the user is creating a new record
        page.tblPassword = m ? m : new model.TblPasswordModel();

        page.modelView.model = page.tblPassword;

        if (page.tblPassword.id == null || page.tblPassword.id == '') {
            // this is a new record, there is no need to contact the server
            page.renderModelView(false);
        } else {
            app.showProgress('modelLoader');

            // fetch the model from the server so we are not updating stale data
            page.tblPassword.fetch({
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

        $('#email').click(function (e) {
            e.preventDefault();
            page.emailModel();
        });
        $('#download').click(function (e) {
            e.preventDefault();
            page.downloadModel();
        });

        if (showDeleteButton) {
            // attach click handlers to the delete buttons

            $('#deleteTblPasswordButton').click(function (e) {
                e.preventDefault();
                $('#confirmDeleteTblPasswordContainer').show('fast');
            });

            $('#cancelDeleteTblPasswordButton').click(function (e) {
                e.preventDefault();
                $('#confirmDeleteTblPasswordContainer').hide('fast');
            });

            $('#confirmDeleteTblPasswordButton').click(function (e) {
                e.preventDefault();
                page.deleteModel();
            });

        } else {
            // no point in initializing the click handlers if we don't show the button
            $('#deleteTblPasswordButtonContainer').hide();
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
        var isNew = page.tblPassword.isNew();

        app.showProgress('modelLoader');

        page.tblPassword.save({
            'salesorder': $('input#salesorder').val(),
            'results': $('textarea#results').val(),
            'time': $('input#time').val() + ' ' + $('input#time-time').val()
        }, {
            wait: true,
            success: function () {
                $('#tblPasswordDetailDialog').modal('hide');
                setTimeout("app.appendAlert('TblPassword was sucessfully " + (isNew ? "inserted" : "updated") + "','alert-success',3000,'collectionAlert')", 500);
                app.hideProgress('modelLoader');

                // if the collection was initally new then we need to add it to the collection now
                if (isNew) {
                    page.tblPasswords.add(page.tblPassword)
                }

                if (model.reloadCollectionOnModelUpdate) {
                    // re-fetch and render the collection after the model has been updated
                    page.fetchTblPasswords(page.fetchParams, true);
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

        page.tblPassword.destroy({
            wait: true,
            success: function () {
                $('#tblPasswordDetailDialog').modal('hide');
                setTimeout("app.appendAlert('The TblPassword record was deleted','alert-success',3000,'collectionAlert')", 500);
                app.hideProgress('modelLoader');

                if (model.reloadCollectionOnModelUpdate) {
                    // re-fetch and render the collection after the model has been updated
                    page.fetchTblPasswords(page.fetchParams, true);
                }
            },
            error: function (model, response, scope) {
                app.appendAlert(app.getErrorMessage(response), 'alert-error', 0, 'modelAlert');
                app.hideProgress('modelLoader');
            }
        });
    },
    /**
     * Download the excel file
     */
    downloadModel: function () {
        // reset any previous errors
        $('#modelAlert').html('');

        var title = 'PMP Data Submission';
        var salesorder = $('#salesorder').val();
        var filename = '';
        var customerACR = "changeMe";
        $.ajax({
            url: "/SPOT/provisioning/api/tblprogresses?salesorder=" + salesorder,
            type: "GET",
            wait: true,
            async: false,
            success: function (data) {
                var getJson = JSON.parse(data.rows[0].data);
                customerACR = getJson.CustomerACR;
                console.log(getJson);

            }

        });

        var table = $('textarea#results').val();
        var t = document.getElementById('listing_smaller');
        var ID = $(t.rows[1].cells[0]).text();
        ID = $.trim(ID);
        if (ID === "")
            ID = 'ID';
        filename = 'PO_' + salesorder + '-' + customerACR + '-System_' + ID;

        /*
         * Put all data in session
         */


        var Jdata = {
            SALESORDER: salesorder,
            filename: filename,
            tablevar: table,
            title: title,
            var : 'tablevar',
            debug: true,
            confidential: 'Strictly Confidential'
        };

        var JstrtoSend = JSON.stringify(Jdata)
        var session = 'data=' + JstrtoSend;

        app.showProgress('modelLoader');
        // reload session within new values
        $.ajax({
            url: "includes/loadSession.php",
            type: "POST",
            data: session,
            wait: true,
            success: function () {
                // All OK , pass to phase 2
                var url = "includes/excelexport_pass.php";


                $.ajax({
                    url: url,
                    type: "POST",
                    wait: true,
                    success: function (output) {
                        window.location.href = url;
                        //   var Jdata = JSON.parse(output);
                        // var url = Jdata.url;
                        // $('#downloadlink').html(' <a href="' + url + '" class="btn btn-mini btn-info"><i class="icon-download icon-white"></i> Download The file</a>');
                        // $('#tblPasswordDetailDialog').modal('hide');
                        setTimeout("app.appendAlert('Excel file created','alert-success',3000,'collectionAlert')", 500);
                        console.log(output);
                        $('#email').show();
                        app.hideProgress('modelLoader');
                        /*
                         * Workaround to save file on server as well
                         */
                        url = "includes/excelexport_mail_pass.php";
                        $.ajax({
                            url: url,
                            type: "POST",
                            wait: true,
                        });

                    },
                    error: function (model, response, scope) {
                        console.log(response);
                        console.log(model);
                        console.log(scope);
                    }
                });


            }
        });

    },
    emailModel: function () {
        $.ajax({
            url: "/SPOT/provisioning/includes/mail_pass_temp.php",
            type: "POST",
            wait: true,
            success: function (output) {
                $('#tblPasswordDetailDialog').modal('hide');
                setTimeout("app.appendAlert('Check your inbox, Email was sent','alert-success',3000,'collectionAlert')", 500);
                console.log(output);
                $('#email').show();
                app.hideProgress('modelLoader');


            },
            error: function (model, response, scope) {
                console.log(response);
                console.log(model);
                console.log(scope);
            }
        });
    }
};

