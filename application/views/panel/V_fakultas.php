
<!-- start: PAGE -->
      <div class="main-content">
        <div class="container">
          <!-- start: PAGE HEADER -->
          <div class="row">
            <div class="col-sm-12">
              <!-- start: PAGE TITLE & BREADCRUMB -->
              <ol class="breadcrumb">
                <li class="active">
                  <i class="clip-file"></i>
                    <?php echo $pages ?>
                </li>
                <li class="search-box">
                  <form class="sidebar-search">
                    <div class="form-group">
                      <input type="text" placeholder="Start Searching...">
                      <button class="submit">
                        <i class="clip-search-3"></i>
                      </button>
                    </div>
                  </form>
                </li>
              </ol>
              <div class="page-header">
                <h1><?php echo $pages; ?><small><?php echo $penjelasan; ?></small></h1>
              </div>
              <!-- end: PAGE TITLE & BREADCRUMB -->
            </div>
          </div>
          <!-- end: PAGE HEADER -->
          <!-- start: PAGE CONTENT -->

<!-- start: DYNAMIC TABLE PANEL -->
              <div class="panel panel-default">
                <div class="panel-heading">
                  <i class="fa fa-external-link-square"></i>
                  Data Fakultas
                </div>
                <div class="panel-body">
                  <div class="row">
                    <div class="col-md-12 space20">
                      <button class="btn btn-primary" onclick="add_fakultas()">
                        <i class="glyphicon glyphicon-plus"></i> Add Fakultas
                      </button>
                    </div>
                  </div>
                  <table class="table table-striped table-bordered table-hover table-full-width" id="sample_1">
                    <thead>
                      <tr>
                        <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th width="150px">Create at</th>
                            <th width="150px">Action</p></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($fakultas as $f){?>
                             <tr>
                                <td><?php echo $f->fakultas_id; ?></td>
                                <td><?php echo strtoupper($f->fakultas_name); ?></td>
                                <td><?php echo $f->fakultas_desc; ?></td>
                                <td><?php echo $f->fakultas_status; ?></td>
                                <td><?php echo $f->fakultas_create_at; ?></td>
                                <td>

                                <a class="btn btn-xs btn-primary" onclick="upd_fakultas(<?php echo $f->fakultas_id;?>)">
                                   <i class="fa fa-pencil"></i> Edit
                                </a>
                                <a class="btn btn-xs btn-bricky" onclick="del_fakultas(<?php echo $f->fakultas_id;?>)">
                                  <i class="fa fa-trash-o"></i> Delete
                                </a>

                                </td>


                              </tr>
                          <?php }?>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- end: DYNAMIC TABLE PANEL -->
            </div>
          <!-- end: PAGE CONTENT-->
        </div>
      </div>
      <!-- end: PAGE -->

<script type="text/javascript">
  $(document).ready( function () {
      $('#table_id').DataTable();
  } );

    var save_method; //for save method string
    var table;


    function add_fakultas()
    {
      save_method = 'add';
      $('#formfakultas')[0].reset(); // reset form on modals
      $('#modal_form').modal('show'); // show bootstrap modal
    //$('.modal-title').text('Add Person'); // Set Title to Bootstrap modal title
    }

    function upd_fakultas(id)
    {
      save_method = 'update';
      $('#formfakultas')[0].reset(); // reset form on modals

      //Ajax Load data from ajax
      $.ajax({
        url : "<?php echo site_url('index.php/fakultas/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="fakultas_id"]').val(data.fakultas_id);
            $('[name="fakultas_name"]').val(data.fakultas_name);
            $('[name="fakultas_desc"]').val(data.fakultas_desc);
            $('[name="fakultas_status"]').val(data.fakultas_status);
            $('[name="fakultas_create_at"]').val(data.fakultas_create_at);


            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit User'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
    }



    function save()
    {
      var url;
      if(save_method == 'add')
      {
          url = "<?php echo site_url('index.php/fakultas/add_fakultas')?>";
      }
      else
      {
        url = "<?php echo site_url('index.php/fakultas/upd_fakultas')?>";
      }

       // ajax adding data to database
          $.ajax({
            url : url,
            type: "POST",
            data: $('#formfakultas').serialize(),
            dataType: "JSON",
            success: function(data)
            {
               //if success close modal and reload ajax table
               $('#modal_form').modal('hide');
              location.reload();// for reload a page
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('You have some errors. Please check below.');
            }
        });
    }

    function del_fakultas(id)
    {
      if(confirm('Are you sure delete this data?'))
      {
        // ajax delete data from database
          $.ajax({
            url : "<?php echo site_url('index.php/fakultas/del_fakultas')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {

               location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

      }
    }

  </script>

  <!-- Bootstrap modal -->
  <div class="modal fade" id="modal_form" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title"><i class="fa fa-plus-square teal"></i> Fakultas Form</h3>
      </div>
      <div class="modal-body form">
          <!-- start: FORM VALIDATION 1 PANEL -->
                  <form action="" role="formfakultas" id="formfakultas">
                      <input type="hidden" value="" name="fakultas_id"/>
                      <input type="hidden"  name="fakultas_create_at"/>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="errorHandler alert alert-danger no-display">
                          <i class="fa fa-times-sign"></i> You have some form errors. Please check below.
                        </div>
                        <div class="successHandler alert alert-success no-display">
                          <i class="fa fa-ok"></i> Your form validation is successful!
                        </div>
                      </div>
                      <!-- INPUT USERNAME-->
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="control-label">
                            Name <span class="symbol required"></span>
                          </label>
                          <input type="text" placeholder="Insert your fakultas name" class="form-control" id="fakultas_name" name="fakultas_name">
                        </div>
                        <!-- INPUT DESCRIPTION-->
                        <div class="form-group">
                          <label class="control-label">
                            Description <span class="symbol required"></span>
                          </label>
                          <textarea name="fakultas_desc" cplaceholder="Insert your description" class="autosize form-control" id="form-field-24" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 69px;"></textarea>
                        </div>
                        <!-- INPUT STATUS-->
                        <div class="form-group">
                          <label class="control-label">
                            Status <span class="symbol required"></span>
                          </label>
                          <select name="fakultas_status" placeholder="Insert your status" class="form-control">
                              <option value="Active">Active</option>
                              <option value="Deactive">Deactive</option>
                          </select>
                        </div>
                      </div>
                      
                    </div>
             
          </div>
          <div class="modal-footer">
              <p align="left">
              <button class="btn btn-yellow" type="submit" Onclick="save()">
                  Submit <i class="fa fa-arrow-circle-right"></i>
              </button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </p>
          </div>
          </form>
           <!-- end: FORM VALIDATION 1 PANEL -->

        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  <!-- End Bootstrap modal -->