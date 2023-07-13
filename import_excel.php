<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.css"> 

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <section class="content">
    <!-- For Messages -->
    <?php $this->load->view('admin/includes/_messages.php') ?>
    <div class="card">
      <div class="card-header">
        <div class="d-inline-block">
         <!--  <h3 class="card-title"><i class="fa fa-list"></i>&nbsp; <?= trans('users_list') ?></h3> -->
<!--            <h3><a href="<?php echo base_url();?>admin/users" class="btn btn-info float-left">Back</a></h3>
 -->
        </div>

        <div class="d-inline-block float-right">

          <?php if($this->rbac->check_operation_permission('add')): ?>
            <a href="<?= base_url('admin/users/add'); ?>" class="btn btn-success"><i class="fa fa-plus"></i> <?= trans('add_new_user') ?></a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-body table-responsive">
        <form action="<?php echo base_url();?>admin/admin/import_excel_data" method="post" enctype="multipart/form-data">

          <input type="file" name="excel" class="form-control">
         <button style="float: left;list-style-type: none;" class="btn btn-primary">  
                                    Excel Upload
                              
                            </button>
            </form>                
           <div class="card-body table-responsive">
        <table id="na_datatable" class="table table-bordered table-striped" width="100%">
          <thead>
            <tr>
              <th>Employee ID</th>
              <th>Full Name</th>
              <th>Job Title</th>
              <th>Department</th>
              <th>Business Unit </th>
              <th>Gender </th>
              <th>Ethncity </th>
              <th>Age</th>
              <th>Hire Date</th>
              <th>Salary </th>
              <th>Bonus </th>
              <th>Country </th>
              <th>City </th>
              <th>Exit Date</th>
            </tr>
          </thead>
            <tbody>
              <?php foreach ($excel_data as  $row){ ?>
                <tr>
                  <td><?php echo $row['emp_id'];?></td>
                    <td><?php echo $row['full_name'];?></td>
                    <td><?php echo $row['job_title'];?></td>  
                   <td><?php echo $row['department'];?></td>   
                   <td><?php echo $row['unit'];?></td>
                   <td><?php echo $row['gender'];?></td>  
                  <td><?php echo $row['ethnicity'];?></td> 
                   <td><?php echo $row['age'];?></td>   
                  <td><?php echo $row['hire_date'];?></td>  
                  <td><?php echo $row['annual_salary'];?></td>  
                  <td><?php echo $row['bonus'];?></td> 
                   <td><?php echo $row['country'];?></td> 
                   <td><?php echo $row['city'];?></td>  
                  <td><?php echo $row['exit_date'];?></td>
                </tr>
             <?php } ?>
              
            </tbody>

         
        </table>
        </div>                  

          
                       
  </section>  

</div>

<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>
<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script type="text/javascript" src="http://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<!-- DataTables -->
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>

<script>
  $('#na_datatable').DataTable();
  //---------------------------------------------------
 
</script>


<script type="text/javascript">
  $("body").on("change",".tgl_checkbox",function(){
    console.log('checked');
    $.post('<?=base_url("admin/users/change_status")?>',
    {
      '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
      id : $(this).data('id'),
      status : $(this).is(':checked') == true?1:0
    },
    function(data){
      $.notify("Status Changed Successfully", "success");
    });
  });


</script>
 <script >
  $(document).ready(function () {
      $("#excellExport").click(function(){
        TableToExcel.convert(document.getElementById("na_datatable"), {
            name: "users_list.xlsx",
            sheet: {
            name: "Sheet1"
            }
          });
        });
  });
</script> 
<script >
  $(document).ready(function () {
      $("#excellExport").click(function(){
        TableToExcel.convert(document.getElementById("memberList"), {
            name: "users_list.xlsx",
            sheet: {
            name: "Sheet1"
            }
          });
        });
  });
</script>
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
    <!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> -->  
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
   
    <script type="text/javascript">
        $("body").on("click", "#pdfExport", function () {
            html2canvas($('#na_datatable')[0], {
                onrendered: function (canvas) {
                    var data = canvas.toDataURL();
                    var allMembersDataInformation = {
                        content: [{
                            image: data,
                            width: 500
                        }]
                    };
                    pdfMake.createPdf(allMembersDataInformation).download("member-details.pdf");
                }
            });
        });
    </script>          
<script type="text/javascript">
        function tableToCSV() {
 
            // Variable to store the final csv data
            var csv_data = [];
 
            // Get each row data
            var rows = document.getElementsByTagName('tr');
            for (var i = 0; i < rows.length; i++) {
 
                // Get each column data
                var cols = rows[i].querySelectorAll('td,th');
 
                // Stores each csv row data
                var csvrow = [];
                for (var j = 0; j < cols.length; j++) {
 
                    // Get the text data of each cell
                    // of a row and push it to csvrow
                    csvrow.push(cols[j].innerHTML);
                }
 
                // Combine each column value with comma
                csv_data.push(csvrow.join(","));
            }
 
            // Combine each row data with new line character
            csv_data = csv_data.join('\n');
 
            // Call this function to download csv file 
            downloadCSVFile(csv_data);
 
        }
 
        function downloadCSVFile(csv_data) {
 
            // Create CSV file object and feed
            // our csv_data into it
            CSVFile = new Blob([csv_data], {
                type: "text/csv"
            });
 
            // Create to temporary link to initiate
            // download process
            var temp_link = document.createElement('a');
 
            // Download csv file
            temp_link.download = "GfG.csv";
            var url = window.URL.createObjectURL(CSVFile);
            temp_link.href = url;
 
            // This link should not be displayed
            temp_link.style.display = "none";
            document.body.appendChild(temp_link);
 
            // Automatically click the link to
            // trigger download
            temp_link.click();
            document.body.removeChild(temp_link);
        }

        //Create PDf from HTML...
  function CreatePDFfromHTML() {
      var HTML_Width = $("#na_datatable").width();
      var HTML_Height = $("#na_datatable").height();
      var top_left_margin = 15;
      var PDF_Width = HTML_Width + (top_left_margin * 2);
      var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);
      var canvas_image_width = HTML_Width;
      var canvas_image_height = HTML_Height;

      var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;

      html2canvas($("#na_datatable")[0]).then(function (canvas) {
          var imgData = canvas.toDataURL("image/jpeg", 1.0);
          var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
          pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);
          for (var i = 1; i <= totalPDFPages; i++) { 
              pdf.addPage(PDF_Width, PDF_Height);
              pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
          }
          pdf.save("users_list.pdf");
         // $(".panel-body").show();
      });
  }
    </script>   


