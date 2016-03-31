/**
 * View logic for Provisioningnotificationses
 */

/**
 * application logic specific to the Provisioningnotifications listing page
 */
var page = {
    provisioningnotificationses: new model.ProvisioningnotificationsCollection(),
    collectionView: null,
    provisioningnotifications: null,
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
        $('[data-toggle="tooltip"]').tooltip();

        if (!$.isReady && console)
            console.warn('page was initialized before dom is ready.  views may not render properly.');

        // make the new button clickable
        $("#newProvisioningnotificationsButton").click(function (e) {
            e.preventDefault();
            page.showDetailDialog();
        });

        // let the page know when the dialog is open
        $('#provisioningnotificationsDetailDialog').on('show', function () {
            page.dialogIsOpen = true;
        });

        // when the model dialog is closed, let page know and reset the model view
        $('#provisioningnotificationsDetailDialog').on('hidden', function () {
            $('#modelAlert').html('');
            page.dialogIsOpen = false;
        });

        // save the model when the save button is clicked
        $("#saveProvisioningnotificationsButton").click(function (e) {
            e.preventDefault();
            page.updateModel();
        });

        // initialize the collection view
        this.collectionView = new view.CollectionView({
            el: $("#provisioningnotificationsCollectionContainer"),
            templateEl: $("#provisioningnotificationsCollectionTemplate"),
            collection: page.provisioningnotificationses
        });





        // initialize the search filter






        $('#filter').on('click', function (obj) {


            page.fetchParams.filter = $('#filter').val();
            page.fetchParams.page = 1;
            page.fetchProvisioningnotificationses(page.fetchParams);
        });


        // make the rows clickable ('rendered' is a custom event, not a standard backbone event)
        this.collectionView.on('rendered', function () {

            // attach click handler to the table rows for editing
            $('table.collection tbody tr').click(function (e) {
                e.preventDefault();
                var m = page.provisioningnotificationses.get(this.id);
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
                page.fetchProvisioningnotificationses(page.fetchParams);
            });

            // attach click handlers to the pagination controls
            $('.pageButton').click(function (e) {
                e.preventDefault();
                page.fetchParams.page = this.id.substr(5);
                page.fetchProvisioningnotificationses(page.fetchParams);
            });

            page.isInitialized = true;
            page.isInitializing = false;
        });

        // backbone docs recommend bootstrapping data on initial page load, but we live by our own rules!
        this.fetchProvisioningnotificationses({page: 1});

        // initialize the model view
        this.modelView = new view.ModelView({
            el: $("#provisioningnotificationsModelContainer")
        });

        // tell the model view where it's template is located
        this.modelView.templateEl = $("#provisioningnotificationsModelTemplate");

        if (model.longPollDuration > 0) {
            setInterval(function () {

                if (!page.dialogIsOpen) {
                    page.fetchProvisioningnotificationses(page.fetchParams, true);
                }

            }, model.longPollDuration);
        }
    }
    ,
    /**
     * Fetch the collection data from the server
     * @param object params passed through to collection.fetch
     * @param bool true to hide the loading animation
     */
    fetchProvisioningnotificationses: function (params, hideLoader) {
        // persist the params so that paging/sorting/filtering will play together nicely
        page.fetchParams = params;

        if (page.fetchInProgress) {
            if (console)
                console.log('supressing fetch because it is already in progress');
        }

        page.fetchInProgress = true;

        if (!hideLoader)
            app.showProgress('loader');





        page.provisioningnotificationses.fetch({
            data: params,
            success: function () {

                if (page.provisioningnotificationses.collectionHasChanged) {
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

        //Remove html in .results div
        
        $('.results').html('');
        // show the modal dialog
        $('#provisioningnotificationsDetailDialog').modal({show: true});

        // if a model was specified then that means a user is editing an existing record
        // if not, then the user is creating a new record
        page.provisioningnotifications = m ? m : new model.ProvisioningnotificationsModel();

        page.modelView.model = page.provisioningnotifications;

        if (page.provisioningnotifications.id == null || page.provisioningnotifications.id == '') {
            // this is a new record, there is no need to contact the server
            page.renderModelView(false);
        } else {
            app.showProgress('modelLoader');

            // fetch the model from the server so we are not updating stale data
            page.provisioningnotifications.fetch({
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
        $('#infos').html($('textarea#hostname').val());
        $('#imgOs').html(img);

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

            $('#pdf').on('click', function (e) {
                e.preventDefault();
                var base64img = $(img).attr('src');
                $.ajax({
                    url: "includes/loadSession.php",
                    type: "POST",
                    cache: false,
                    //htmlDiv is take from template, no need to define here
                    //it contains the table var
                    data: {datatoPdf: htmlDiv},
                    success: function (data) {

                        $.ajax({
                            url: "includes/loadSession.php",
                            type: "POST",
                            async: false,
                            wait: true,
                            timeout: 30000,
                            data: {image64enc: base64img},
                            error: function () {
                                return true;
                            },
                            success: function () {
                                var title = $('textarea#hostname').val() + '_' + $('textarea#configuredip').val() + '.pdf';


                                console.log(base64img);
                                var url = 'libs/App/pdfexport.php?var=datatoPdf&debug=true&title=' + title + '&image=imageOS';
                                url = url + (-1 === url.indexOf('?') ? '?' : '&') + "__=" + Number(new Date())

                                $('#provisioningnotificationsDetailDialog').modal('hide');

                                window.location.href = url;


                            }


                        });
                    }

                });


            });
            $('#excel').on('click', function (e) {
                e.preventDefault();
                $.ajax({
                    url: "includes/loadSession.php",
                    type: "POST",
                    //htmlDiv is take from template, no need to define here
                    //it contains the table var
                    data: {datatoPdf: htmlDiv},
                    success: function (data) {
                        var title = $('textarea#hostname').val() + '_' + $('textarea#configuredip').val() + '.xlsx';

                        var url = 'libs/App/excelexport.php?var=datatoPdf&debug=false&title=' + title;
                        $('#provisioningnotificationsDetailDialog').modal('hide');
                        window.location.href = url;

                    }
                });


            });
            // attach click handlers to the delete buttons

            $('#deleteProvisioningnotificationsButton').click(function (e) {
                e.preventDefault();
                $('#confirmDeleteProvisioningnotificationsContainer').show('fast');
            });

            $('#cancelDeleteProvisioningnotificationsButton').click(function (e) {
                e.preventDefault();
                $('#confirmDeleteProvisioningnotificationsContainer').hide('fast');
            });

            $('#confirmDeleteProvisioningnotificationsButton').click(function (e) {
                e.preventDefault();
                page.deleteModel();
            });

        } else {
            // no point in initializing the click handlers if we don't show the button
            $('#deleteProvisioningnotificationsButtonContainer').hide();
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
        var isNew = page.provisioningnotifications.isNew();

        app.showProgress('modelLoader');

        page.provisioningnotifications.save({
            'notifid': $('input#notifid').val(),
            'hostname': $('textarea#hostname').val(),
            'installationip': $('textarea#installationip').val(),
            'configuredip': $('textarea#configuredip').val(),
            'startdate': $('input#startdate').val() + ' ' + $('input#startdate-time').val(),
            'status': $('textarea#status').val(),
            'progress': $('input#progress').val(),
            'image': $('textarea#image').val(),
            'firmware': $('textarea#firmware').val(),
            'ram': $('textarea#ram').val(),
            'cpu': $('textarea#cpu').val(),
            'diskscount': $('textarea#diskscount').val(),
            'netintcount': $('textarea#netintcount').val(),
            'model': $('textarea#model').val(),
            'serial': $('textarea#serial').val(),
            'os': $('textarea#os').val(),
            'update': $('input#update').val() + ' ' + $('input#update-time').val()
        }, {
            wait: true,
            success: function () {
                $('#provisioningnotificationsDetailDialog').modal('hide');
                setTimeout("app.appendAlert('Provisioningnotifications was sucessfully " + (isNew ? "inserted" : "updated") + "','alert-success',3000,'collectionAlert')", 500);
                app.hideProgress('modelLoader');

                // if the collection was initally new then we need to add it to the collection now
                if (isNew) {
                    page.provisioningnotificationses.add(page.provisioningnotifications)
                }

                if (model.reloadCollectionOnModelUpdate) {
                    // re-fetch and render the collection after the model has been updated
                    page.fetchProvisioningnotificationses(page.fetchParams, true);
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

        page.provisioningnotifications.destroy({
            wait: true,
            success: function () {
                $('#provisioningnotificationsDetailDialog').modal('hide');
                setTimeout("app.appendAlert('The Provisioningnotifications record was deleted','alert-success',3000,'collectionAlert')", 500);
                app.hideProgress('modelLoader');

                if (model.reloadCollectionOnModelUpdate) {
                    // re-fetch and render the collection after the model has been updated
                    page.fetchProvisioningnotificationses(page.fetchParams, true);
                }
            },
            error: function (model, response, scope) {
                app.appendAlert(app.getErrorMessage(response), 'alert-error', 0, 'modelAlert');
                app.hideProgress('modelLoader');
            }
        });
    }
};

