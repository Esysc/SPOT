/**
 * View logic for Provisioningscriptses
 */

/**
 * application logic specific to the Provisioningscripts listing page
 */
var page = {
    provisioningscriptses: new model.ProvisioningscriptsCollection(),
    collectionView: null,
    provisioningscripts: null,
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
        $("#newProvisioningscriptsButton").click(function (e) {
            e.preventDefault();
            page.showDetailDialog();
        });

        // let the page know when the dialog is open
        $('#provisioningscriptsDetailDialog').on('show', function () {
            page.dialogIsOpen = true;
        });

        // when the model dialog is closed, let page know and reset the model view
        $('#provisioningscriptsDetailDialog').on('hidden', function () {
            $('#modelAlert').html('');
            page.dialogIsOpen = false;
        });

        // save the model when the save button is clicked
        $("#saveProvisioningscriptsButton").click(function (e) {
            e.preventDefault();
            page.updateModel();
        });

        // initialize the collection view
        this.collectionView = new view.CollectionView({
            el: $("#provisioningscriptsCollectionContainer"),
            templateEl: $("#provisioningscriptsCollectionTemplate"),
            collection: page.provisioningscriptses
        });

        // initialize the search filter
        $('#filter').change(function (obj) {
            page.fetchParams.filter = $('#filter').val();
            page.fetchParams.page = 1;
            page.fetchProvisioningscriptses(page.fetchParams);
        });

        // make the rows clickable ('rendered' is a custom event, not a standard backbone event)
        this.collectionView.on('rendered', function () {

            // attach click handler to the table rows for editing
            $('table.collection tbody tr').click(function (e) {
                e.preventDefault();
                var m = page.provisioningscriptses.get(this.id);
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
                page.fetchProvisioningscriptses(page.fetchParams);
            });

            // attach click handlers to the pagination controls
            $('.pageButton').click(function (e) {
                e.preventDefault();
                page.fetchParams.page = this.id.substr(5);
                page.fetchProvisioningscriptses(page.fetchParams);
            });


            page.isInitialized = true;
            page.isInitializing = false;
        });

        // backbone docs recommend bootstrapping data on initial page load, but we live by our own rules!
        this.fetchProvisioningscriptses({page: 1});

        // initialize the model view
        this.modelView = new view.ModelView({
            el: $("#provisioningscriptsModelContainer")
        });

        // tell the model view where it's template is located
        this.modelView.templateEl = $("#provisioningscriptsModelTemplate");

        if (model.longPollDuration > 0) {
            setInterval(function () {

                if (!page.dialogIsOpen) {
                    page.fetchProvisioningscriptses(page.fetchParams, true);
                }

            }, model.longPollDuration);
        }
    },
    /**
     * Fetch the collection data from the server
     * @param object params passed through to collection.fetch
     * @param bool true to hide the loading animation
     */
    fetchProvisioningscriptses: function (params, hideLoader) {
        // persist the params so that paging/sorting/filtering will play together nicely
        page.fetchParams = params;

        if (page.fetchInProgress) {
            if (console)
                console.log('supressing fetch because it is already in progress');
        }

        page.fetchInProgress = true;

        if (!hideLoader)
            app.showProgress('loader');

        page.provisioningscriptses.fetch({
            data: params,
            success: function () {

                if (page.provisioningscriptses.collectionHasChanged) {
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
        $('#provisioningscriptsDetailDialog').modal({show: true});

        // if a model was specified then that means a user is editing an existing record
        // if not, then the user is creating a new record
        page.provisioningscripts = m ? m : new model.ProvisioningscriptsModel();

        page.modelView.model = page.provisioningscripts;

        if (page.provisioningscripts.id == null || page.provisioningscripts.id == '') {
            // this is a new record, there is no need to contact the server
            page.renderModelView(false);
        } else {
            app.showProgress('modelLoader');

            // fetch the model from the server so we are not updating stale data
            page.provisioningscripts.fetch({
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
                        scrollbarStyle: "native"
                    };

                    var editor = CodeMirror.fromTextArea(document.getElementById("scriptcontent"), config);
                    
                    function selectTheme() {
                        editor.setOption("theme", "solarized dark");
                    }
                    setTimeout(selectTheme, 1000);
                    editor.refresh();
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

        // populate the dropdown options for scripttarget
        // TODO: load only the selected value, then fetch all options when the drop-down is clicked
        var scripttargetValues = new model.MediatypeCollection();
        scripttargetValues.fetch({
            success: function (c) {
                var dd = $('#scripttarget');
                dd.append('<option value=""></option>');
                c.forEach(function (item, index) {
                    dd.append(app.getOptionHtml(
                            item.get('id'),
                            item.get('media'), // TODO: change fieldname if the dropdown doesn't show the desired column
                            page.provisioningscripts.get('scripttarget') == item.get('id')
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


        $('#scriptdownload').on('click', function () {
            var fileid = page.provisioningscripts.get('scriptid');
            window.location = 'includes/fileDownload.php?fileid=' + fileid;
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
                success: function(reponse) {
                    
                    $('#scriptcontent').val(reponse);
                }

            });
        });
        if (showDeleteButton) {
            // attach click handlers to the delete buttons

            $('#deleteProvisioningscriptsButton').click(function (e) {
                e.preventDefault();
                $('#confirmDeleteProvisioningscriptsContainer').show('fast');
            });
            $('#cancelDeleteProvisioningscriptsButton').click(function (e) {
                e.preventDefault();
                $('#confirmDeleteProvisioningscriptsContainer').hide('fast');
            });
            $('#confirmDeleteProvisioningscriptsButton').click(function (e) {
                e.preventDefault();
                page.deleteModel();
            });
        } else {
            // no point in initializing the click handlers if we don't show the button
            $('#deleteProvisioningscriptsButtonContainer').hide();
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
        var isNew = page.provisioningscripts.isNew();
        app.showProgress('modelLoader');
        page.provisioningscripts.save({
            'scriptid': $('input#scriptid').val(),
            'scripttarget': $('select#scripttarget').val(),
            'scriptname': $('input#scriptname').val(),
            'scriptdescription': $('textarea#scriptdescription').val(),
            'scriptcontent': $('textarea#scriptcontent').val(),
            'interpreter': $('input#interpreter').val(),
            'version': $('input#version').val()
        }, {
            wait: true,
            success: function () {
                $('#provisioningscriptsDetailDialog').modal('hide');
                setTimeout("app.appendAlert('Provisioningscripts was sucessfully " + (isNew ? "inserted" : "updated") + "','alert-success',3000,'collectionAlert')", 500);
                app.hideProgress('modelLoader');
                // if the collection was initally new then we need to add it to the collection now
                if (isNew) {
                    page.provisioningscriptses.add(page.provisioningscripts)
                }

                if (model.reloadCollectionOnModelUpdate) {
                    // re-fetch and render the collection after the model has been updated
                    page.fetchProvisioningscriptses(page.fetchParams, true);
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

        page.provisioningscripts.destroy({
            wait: true,
            success: function () {
                $('#provisioningscriptsDetailDialog').modal('hide');
                setTimeout("app.appendAlert('The Provisioningscripts record was deleted','alert-success',3000,'collectionAlert')", 500);
                app.hideProgress('modelLoader');

                if (model.reloadCollectionOnModelUpdate) {
                    // re-fetch and render the collection after the model has been updated
                    page.fetchProvisioningscriptses(page.fetchParams, true);
                }
            },
            error: function (model, response, scope) {
                app.appendAlert(app.getErrorMessage(response), 'alert-error', 0, 'modelAlert');
                app.hideProgress('modelLoader');
            }
        });
    }
};

