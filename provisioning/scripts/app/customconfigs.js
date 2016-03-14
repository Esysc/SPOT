/**
 * View logic for Customconfigs
 */

/**
 * application logic specific to the Customconfig listing page
 */
var page = {
    customconfigs: new model.CustomconfigCollection(),
    collectionView: null,
    customconfig: null,
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
        $("#newCustomconfigButton").click(function (e) {
            e.preventDefault();
            page.showDetailDialog();
        });

        // let the page know when the dialog is open
        $('#customconfigDetailDialog').on('show', function () {
            page.dialogIsOpen = true;
        });

        // when the model dialog is closed, let page know and reset the model view
        $('#customconfigDetailDialog').on('hidden', function () {
            $('#modelAlert').html('');
            page.dialogIsOpen = false;
        });

        // save the model when the save button is clicked
        $("#saveCustomconfigButton").click(function (e) {
            e.preventDefault();
            page.updateModel();
        });

        // initialize the collection view
        this.collectionView = new view.CollectionView({
            el: $("#customconfigCollectionContainer"),
            templateEl: $("#customconfigCollectionTemplate"),
            collection: page.customconfigs
        });

        // initialize the search filter
        $('#filter').change(function (obj) {
            page.fetchParams.filter = $('#filter').val();
            page.fetchParams.page = 1;
            page.fetchCustomconfigs(page.fetchParams);
        });

        // make the rows clickable ('rendered' is a custom event, not a standard backbone event)
        this.collectionView.on('rendered', function () {

            // attach click handler to the table rows for editing
            $('table.collection tbody tr').click(function (e) {
                e.preventDefault();
                var m = page.customconfigs.get(this.id);
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
                page.fetchCustomconfigs(page.fetchParams);
            });

            // attach click handlers to the pagination controls
            $('.pageButton').click(function (e) {
                e.preventDefault();
                page.fetchParams.page = this.id.substr(5);
                page.fetchCustomconfigs(page.fetchParams);
            });

            page.isInitialized = true;
            page.isInitializing = false;
        });

        // backbone docs recommend bootstrapping data on initial page load, but we live by our own rules!
        this.fetchCustomconfigs({page: 1});

        // initialize the model view
        this.modelView = new view.ModelView({
            el: $("#customconfigModelContainer")
        });

        // tell the model view where it's template is located
        this.modelView.templateEl = $("#customconfigModelTemplate");

        if (model.longPollDuration > 0) {
            setInterval(function () {

                if (!page.dialogIsOpen) {
                    page.fetchCustomconfigs(page.fetchParams, true);
                }

            }, model.longPollDuration);
        }
    },
    /**
     * Fetch the collection data from the server
     * @param object params passed through to collection.fetch
     * @param bool true to hide the loading animation
     */
    fetchCustomconfigs: function (params, hideLoader) {
        // persist the params so that paging/sorting/filtering will play together nicely
        page.fetchParams = params;

        if (page.fetchInProgress) {
            if (console)
                console.log('supressing fetch because it is already in progress');
        }

        page.fetchInProgress = true;

        if (!hideLoader)
            app.showProgress('loader');

        page.customconfigs.fetch({
            data: params,
            success: function () {

                if (page.customconfigs.collectionHasChanged) {
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
        $('#customconfigDetailDialog').modal({show: true});

        // if a model was specified then that means a user is editing an existing record
        // if not, then the user is creating a new record
        page.customconfig = m ? m : new model.CustomconfigModel();

        page.modelView.model = page.customconfig;

        if (page.customconfig.id == null || page.customconfig.id == '') {
            // this is a new record, there is no need to contact the server
            page.renderModelView(false);
        } else {
            app.showProgress('modelLoader');

            // fetch the model from the server so we are not updating stale data
            page.customconfig.fetch({
                success: function () {
                    // data returned from the server.  render the model view
                    page.renderModelView(true);
                    
                    var config, editor;
                    config = {
                        lineNumbers: true,
                        mode: "text/html",
                        theme: "ambiance",
                        indentWithTabs: true,
                        readOnly: true,
                        sideBar: true
                    };

                     editor = CodeMirror.fromTextArea(document.getElementById("configContent"), config);

                    function selectTheme() {
                        editor.setOption("theme", "solarized dark");
                    }
                    setTimeout(selectTheme, 1000);


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

        var methodValues = new model.NetworkequipmentCollection();
        methodValues.fetch({
            success: function (c) {

                var dd = $('#configTarget');
                dd.append('<option value=""></option>');
                c.forEach(function (item, index) {
                    dd.append(app.getOptionHtml(
                            item.get('equipId'),
                            item.get('equipModel'), // TODO: change fieldname if the dropdown doesn't show the desired column
                            page.customconfig.get('configTarget') == item.get('equipId')
                            ));
                });

                if (!app.browserSucks()) {
                    dd.combobox();
                    $('div.combobox-container + span.help-inline').hide(); // TODO: hack because combobox is making the inline help div have a height
                }

            },
            error: function (collection, response, scope) {
                app.appendAlert(app.getErrorMessage(response), 'alert-error', 0, 'modelAlert');
            }
        });


        $('#configdownload').on('click', function () {
           
            var fileid = page.customconfig.get('configId');
            var encoded = encodeURIComponent(fileid);
            window.location = 'includes/configDownload.php?fileid=' + encoded;
        });
        $('#fileupload').on('change', function (event) {

            var fileinput = $('#fileupload');
            var file_data = fileinput.prop('files')[0];
            var form_data = new FormData();
            form_data.append('fileupload', file_data);
            console.log(file_data);
            $.ajax({
                type: "POST",
                dataType: 'text',
                url: 'includes/fileUpload.php',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function (reponse) {

                    $('#configContent').val(reponse);
                }

            });
        });

        if (showDeleteButton) {
            // attach click handlers to the delete buttons

            $('#deleteCustomconfigButton').click(function (e) {
                e.preventDefault();
                $('#confirmDeleteCustomconfigContainer').show('fast');
            });

            $('#cancelDeleteCustomconfigButton').click(function (e) {
                e.preventDefault();
                $('#confirmDeleteCustomconfigContainer').hide('fast');
            });

            $('#confirmDeleteCustomconfigButton').click(function (e) {
                e.preventDefault();
                page.deleteModel();
            });

        } else {
            // no point in initializing the click handlers if we don't show the button
            $('#deleteCustomconfigButtonContainer').hide();
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
        var isNew = page.customconfig.isNew();

        app.showProgress('modelLoader');

        page.customconfig.save({
            'salesorder': $('input#salesorder').val(),
            'configTarget': $('select#configTarget').val(),
            'configContent': $('textarea#configContent').val()
        }, {
            wait: true,
            success: function () {
                $('#customconfigDetailDialog').modal('hide');
                setTimeout("app.appendAlert('Customconfig was sucessfully " + (isNew ? "inserted" : "updated") + "','alert-success',3000,'collectionAlert')", 500);
                app.hideProgress('modelLoader');

                // if the collection was initally new then we need to add it to the collection now
                if (isNew) {
                    page.customconfigs.add(page.customconfig)
                }

                if (model.reloadCollectionOnModelUpdate) {
                    // re-fetch and render the collection after the model has been updated
                    page.fetchCustomconfigs(page.fetchParams, true);
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

        page.customconfig.destroy({
            wait: true,
            success: function () {
                $('#customconfigDetailDialog').modal('hide');
                setTimeout("app.appendAlert('The Customconfig record was deleted','alert-success',3000,'collectionAlert')", 500);
                app.hideProgress('modelLoader');

                if (model.reloadCollectionOnModelUpdate) {
                    // re-fetch and render the collection after the model has been updated
                    page.fetchCustomconfigs(page.fetchParams, true);
                }
            },
            error: function (model, response, scope) {
                app.appendAlert(app.getErrorMessage(response), 'alert-error', 0, 'modelAlert');
                app.hideProgress('modelLoader');
            }
        });
    }
};

