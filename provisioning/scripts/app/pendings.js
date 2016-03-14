/**
 * View logic for Tblstoredorderses
 */

/**
 * application logic specific to the Tblstoredorders listing page
 */

var page = {
    tblstoredorderses: new model.TblstoredordersCollection(),
    
    collectionView: null,
    tblstoredorders: null,
    modelView: null,
    isInitialized: false,
    isInitializing: false,
    fetchParams: {filter: '', orderBy: '', orderDesc: '', page: 1},
    fetchInProgress: false,
    dialogIsOpen: false,
    /**
     *
     */
    init: function() {
        // ensure initialization only occurs once
        if (page.isInitialized || page.isInitializing)
            return;
        page.isInitializing = true;

        if (!$.isReady && console)
            console.warn('page was initialized before dom is ready.  views may not render properly.');



        // let the page know when the dialog is open
        $('#tblstoredordersDetailDialog').on('show', function() {
            page.dialogIsOpen = true;
        });

        // when the model dialog is closed, let page know and reset the model view
        $('#tblstoredordersDetailDialog').on('hidden', function() {
            $('#modelAlert').html('');
            page.dialogIsOpen = false;
        });

        // save the model when the save button is clicked
        $("#saveTblstoredordersButton").click(function(e) {
            e.preventDefault();
            page.updateModel();
        });

        // initialize the collection view
        this.collectionView = new view.CollectionView({
            el: $("#tblstoredordersCollectionContainer"),
            templateEl: $("#tblstoredordersCollectionTemplate"),
            collection: page.tblstoredorderses
        });

        // initialize the search filter
        $('#filter').change(function(obj) {
            page.fetchParams.filter = $('#filter').val();
            page.fetchParams.page = 1;
            page.fetchTblstoredorderses(page.fetchParams);
        });

        // make the rows clickable ('rendered' is a custom event, not a standard backbone event)
        this.collectionView.on('rendered', function() {

            // attach click handler to the table rows for editing
            /*		$('table.collection tbody tr').click(function(e) {
             e.preventDefault();
             var m = page.tblstoredorderses.get(this.id);
             //page.showDetailDialog(m);
             }); */
            // attach click handler to the table rows to load specific sales order
            $('table.collection tbody tr').click(function(e) {
                e.preventDefault();
                var m = page.tblstoredorderses.get(this.id);
                //  console.log(m.attributes);

                page.chooseAndSetOrder(m.attributes);

                //page.showDetailDialog(m);
            });
            // make the headers clickable for sorting
            /**$('table.collection thead tr th').click(function(e) {
             e.preventDefault();
             var prop = this.id.replace('header_','');
             
             // toggle the ascending/descending before we change the sort prop
             page.fetchParams.orderDesc = (prop == page.fetchParams.orderBy && !page.fetchParams.orderDesc) ? '1' : '';
             page.fetchParams.orderBy = prop;
             page.fetchParams.page = 1;
             page.fetchTblstoredorderses(page.fetchParams);
             });**/

            // attach click handlers to the pagination controls
            $('.pageButton').click(function(e) {
                e.preventDefault();
                page.fetchParams.page = this.id.substr(5);
                page.fetchTblstoredorderses(page.fetchParams);
            });
            $(document).ready(function() {
                $('[data-toggle=tooltip]').tooltip();
            });


            page.isInitialized = true;
            page.isInitializing = false;
        });

        // backbone docs recommend bootstrapping data on initial page load, but we live by our own rules!
        this.fetchTblstoredorderses({page: 1});

        // initialize the model view
        this.modelView = new view.ModelView({
            el: $("#tblstoredordersModelContainer")
        });

        // tell the model view where it's template is located
        this.modelView.templateEl = $("#tblstoredordersModelTemplate");

        if (model.longPollDuration > 0) {
            setInterval(function() {

                if (!page.dialogIsOpen) {
                    page.fetchTblstoredorderses(page.fetchParams, true);
                }

            }, model.longPollDuration);
        }
    },
    /**
     * Fetch the collection data from the server
     * @param object params passed through to collection.fetch
     * @param bool true to hide the loading animation
     */
    fetchTblstoredorderses: function(params, hideLoader) {
        // persist the params so that paging/sorting/filtering will play together nicely
        page.fetchParams = params;

        if (page.fetchInProgress) {
            if (console)
                console.log('supressing fetch because it is already in progress');
        }

        page.fetchInProgress = true;

        if (!hideLoader)
            app.showProgress('loader');

        page.tblstoredorderses.fetch({
            data: params,
            success: function() {

                if (page.tblstoredorderses.collectionHasChanged) {
                    // TODO: add any logic necessary if the collection has changed
                    // the sync event will trigger the view to re-render
                }

                app.hideProgress('loader');
                page.fetchInProgress = false;
            },
            error: function(m, r) {
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
    showDetailDialog: function(m) {

        // show the modal dialog
        $('#tblstoredordersDetailDialog').modal({show: true});

        // if a model was specified then that means a user is editing an existing record
        // if not, then the user is creating a new record
        page.tblstoredorders = m ? m : new model.TblstoredordersModel();

        page.modelView.model = page.tblstoredorders;

        if (page.tblstoredorders.id == null || page.tblstoredorders.id == '') {
            // this is a new record, there is no need to contact the server
            page.renderModelView(false);
        } else {
            app.showProgress('modelLoader');

            // fetch the model from the server so we are not updating stale data
            page.tblstoredorders.fetch({
                success: function() {
                    // data returned from the server.  render the model view
                    page.renderModelView(true);
                },
                error: function(m, r) {
                    app.appendAlert(app.getErrorMessage(r), 'alert-error', 0, 'modelAlert');
                    app.hideProgress('modelLoader');
                }

            });
        }

    },
    chooseAndSetOrder: function(attr) {
        $('#modelAlert').html('');
        $('.control-group').removeClass('error');
        $('.help-inline').html('');

        
        var user = $('#username').val();
        var currentdate = new Date();
        var datetime = currentdate.getDate() + "/"
                + (currentdate.getMonth() + 1) + "/"
                + currentdate.getFullYear() + " @ "
                + currentdate.getHours() + ":"
                + currentdate.getMinutes() + ":"
                + currentdate.getSeconds();
        var inside = {
           
                CustomerACR: attr.CustomerACR, 
                ProgramManager : attr.ProgramManager,
                pendDate : attr.pendDate,
                pstartDate: attr.pstartDate,
                releasename: attr.releasename
                
            
        };
        var JSONinside = JSON.stringify(inside); 
        var toSend = {user: user, 
            salesorder: attr.salesorder, 
            creationdate: datetime, 
            data: JSONinside
                
            
            
        };
        var JSONdata = JSON.stringify(toSend);
       //   $.put("api/tblprogress", JSONdata, function( data ) {
       $.ajax({
           url: "api/tblprogress",
           type: "POST",
           data: JSONdata,
           wait: true,
            success: function() {
               $('#success').html('Successfully selected sales order ' + attr.salesorder + ' for provisioning. You can now proceed to provision the machines:  <a href=\"./provisioning1\" class=\"btn btn-large btn-primary\">Wizard');
               $('#failed').hide();
                $('#success').show();
                $.ajax({
           url: "includes/loadSession.php",
           type: "POST",
           data: {salesorder : attr.salesorder,
                  data : JSONinside},
           wait: true
                });
            },
            error: function(model, response, scope) {

                $('#failed').html('An error occured, probally the order is already in progress. The error reported is: ' +  scope);
                $('#success').hide();
                 $('#failed').show();
            }
            
    });
        
        //var toSend = JSON.stringify({"user" : user, "salesorder" : salesorder, "creationdate" : datetime, "data" : myObj});
       
     
        
        

    },
    /**
     * Render the model template in the popup
     * @param bool show the delete button
     */
    renderModelView: function(showDeleteButton) {
        page.modelView.render();

        app.hideProgress('modelLoader');

        // initialize any special controls
        try {
            $('.date-picker')
                    .datepicker()
                    .on('changeDate', function(ev) {
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

            $('#deleteTblstoredordersButton').click(function(e) {
                e.preventDefault();
                $('#confirmDeleteTblstoredordersContainer').show('fast');
            });

            $('#cancelDeleteTblstoredordersButton').click(function(e) {
                e.preventDefault();
                $('#confirmDeleteTblstoredordersContainer').hide('fast');
            });

            $('#confirmDeleteTblstoredordersButton').click(function(e) {
                e.preventDefault();
                page.deleteModel();
            });

        } else {
            // no point in initializing the click handlers if we don't show the button
            $('#deleteTblstoredordersButtonContainer').hide();
        }
    },
    /**
     * update the model that is currently displayed in the dialog
     */
    updateModel: function() {
        // reset any previous errors
        $('#modelAlert').html('');
        $('.control-group').removeClass('error');
        $('.help-inline').html('');

        // if this is new then on success we need to add it to the collection
        var isNew = page.tblprogress.isNew();

        app.showProgress('modelLoader');

        page.tblprogress.save({
            'user': $('input#user').val(),
          //  'object': $('input#object').val(),
            'salesorder': m.attributes.salesorder,
          //  'creationdate': $('input#creationdate').val(),
          //  'origin': $('input#origin').val(),
          //  'version': $('input#version').val(),
          //  'status': $('input#status').val(),
          //  'message': $('textarea#message').val()
        }, {
            wait: true,
            success: function() {
                $('#tblstoredordersDetailDialog').modal('hide');
                setTimeout("app.appendAlert('Tblstoredorders was sucessfully " + (isNew ? "inserted" : "updated") + "','alert-success',3000,'collectionAlert')", 500);
                app.hideProgress('modelLoader');

                // if the collection was initally new then we need to add it to the collection now
                if (isNew) {
                    page.tblstoredorderses.add(page.tblstoredorders)
                }

                if (model.reloadCollectionOnModelUpdate) {
                    // re-fetch and render the collection after the model has been updated
                    page.fetchTblstoredorderses(page.fetchParams, true);
                }
            },
            error: function(model, response, scope) {

                app.hideProgress('modelLoader');

                app.appendAlert(app.getErrorMessage(response), 'alert-error', 0, 'modelAlert');

                try {
                    var json = $.parseJSON(response.responseText);

                    if (json.errors) {
                        $.each(json.errors, function(key, value) {
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
    deleteModel: function() {
        // reset any previous errors
        $('#modelAlert').html('');

        app.showProgress('modelLoader');

        page.tblstoredorders.destroy({
            wait: true,
            success: function() {
                $('#tblstoredordersDetailDialog').modal('hide');
                setTimeout("app.appendAlert('The Tblstoredorders record was deleted','alert-success',3000,'collectionAlert')", 500);
                app.hideProgress('modelLoader');

                if (model.reloadCollectionOnModelUpdate) {
                    // re-fetch and render the collection after the model has been updated
                    page.fetchTblstoredorderses(page.fetchParams, true);
                }
            },
            error: function(model, response, scope) {
                app.appendAlert(app.getErrorMessage(response), 'alert-error', 0, 'modelAlert');
                app.hideProgress('modelLoader');
            }
        });
    }
};


