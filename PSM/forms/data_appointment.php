<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Appointment Table - Inline Edit</title>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      border-radius: 16px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      margin-bottom: 40px;
    }
    .table thead {
      background-color: #20c997;
      color: white;
    }
    td[contenteditable] {
      background-color: #fff3cd;
      border: 1px dashed #ffc107;
    }
    .action-buttons .btn {
      margin-right: 5px;
    }
            tfoot input {
      width: 100%;
      box-sizing: border-box;
    }
    #appointmentTable tfoot {
  background: linear-gradient(135deg, rgba(255,255,255,0.25), rgba(255,255,255,0.1));
  backdrop-filter: blur(8px);
  color: #212529; /* Dark text for readability */
}

#appointmentTable tfoot input {
  background:lightgrey;
  color: #212529;
  border: 1px solid rgba(0,0,0,0.1);
  border-radius: 6px;
  padding: 4px 6px;
  outline: none;
}

#appointmentTable tfoot input:focus {
  border-color: #0d6efd; /* Bootstrap primary */
  box-shadow: 0 0 4px rgba(13,110,253,0.5);
}
  </style>
</head>
<body>

<div class="container">
  <div class='card p-4'>
    <h4 class='text-center mb-4'></h4>
    <div class='table-responsive'>
      <table class='table table-bordered table-striped datatable' id='appointmentTable'>
        <thead>
          <tr>
            <th style="background-color:black; color:white;">Appointment ID</th>
            <th style="background-color:black; color:white;">Pet ID</th>
            <th style="background-color:black; color:white;">Doctor ID</th>
            <th style="background-color:black; color:white;">Reason</th>
            <th style="background-color:black; color:white;">Date</th>
            <th style="background-color:black; color:white;">Status</th>
            <th style="background-color:black; color:white;">Actions</th>
          </tr>
        </thead>
                <tfoot>
    <tr>
      <th>Appointment ID</th>
      <th>Pet ID</th>
      <th>Doctor ID</th>
      <th>Reason</th>
      <th>Date</th>
      <th>Status</th>
      <th>Actions</th>

    </tr>
  </tfoot>
        <tbody>
        <?php
        require("connect2.php");
        $result = mysqli_query($conn, "SELECT Aid, Pid, Did, Reason, Date,Status FROM appointment");
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr data-id='{$row['Aid']}' data-table='appointment'>
                    <td class='editable' data-field='Aid'>{$row['Aid']}</td>
                    <td class='editable' data-field='Pid'>{$row['Pid']}</td>
                    <td class='editable' data-field='Did'>{$row['Did']}</td>
                    <td class='editable' data-field='Reason'>{$row['Reason']}</td>
                    <td class='editable' data-field='Date'>{$row['Date']}</td>
                                        <td class='editable' data-field='Status'>{$row['Status']}</td>
                    <td class='action-buttons'>
                      <button class='btn btn-sm btn-warning editBtn'>Edit</button>
                      <button class='btn btn-sm btn-danger deleteBtn'>Delete</button>
                      <a href='appointmentpdf_generation.php?Aid={$row['Aid']}' target='_blank' class='btn btn-sm btn-info'>Print PDF</a>

                    </td>
                  </tr>";
          }
        } else {
          echo "<tr><td colspan='6'>No data found</td></tr>";
        }
        mysqli_close($conn);
        ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
 <script>
$(document).ready(function() {
    // Add input boxes to each footer cell
    $('#appointmentTable tfoot th').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="Search '+title+'" />');
    });

    // Initialize DataTable
    var table = $('#appointmentTable').DataTable();

    // Apply column search
    table.columns().every(function () {
        var that = this;
        $('input', this.footer()).on('keyup change clear', function () {
            if (that.search() !== this.value) {
                that.search(this.value).draw();
            }
        });
    });
});
</script>
<script>
$(document).ready(function(){
  $('.datatable').DataTable();

  $(document).on('click', '.editBtn', function () {
    const btn = $(this);
    const row = btn.closest('tr');
    const isEditing = btn.text() === 'Save';

    if (!isEditing) {
      row.find('.editable').attr('contenteditable', 'true').addClass('editing');
      btn.text('Save');
    } else {
      const id = row.data('id');
      const table = row.data('table');
      let data = { id: id, table: table };

      row.find('.editable').each(function () {
        const field = $(this).data('field');
        const value = $(this).text().trim();
        data[field] = value;
      });

      $.post('update_appointment.php', data, function (response) {
        alert(response);
        row.find('.editable').removeAttr('contenteditable').removeClass('editing');
        btn.text('Edit');
      });
    }
  });

  $(document).on('click', '.deleteBtn', function () {
    if (!confirm("Are you sure you want to delete this record?")) return;
    const row = $(this).closest('tr');
    const id = row.data('id');
    const table = row.data('table');

    $.post('delete_appointment.php', { id: id, table: table }, function (response) {
      alert(response);
      row.remove();
    });
  });
});
</script>

</body>
</html>