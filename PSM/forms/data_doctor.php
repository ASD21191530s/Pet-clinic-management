<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Doctor Details</title>

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <style>
    body {
      background-color: #f0f4f8;
    }
    .card {
      border-radius: 16px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    .table thead {
      background-color: #198754;
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
    #doctorTable tfoot {
  background: linear-gradient(135deg, rgba(255,255,255,0.25), rgba(255,255,255,0.1));
  backdrop-filter: blur(8px);
  color: #212529; /* Dark text for readability */
}

#doctorTable tfoot input {
  background:lightgrey;
  color: #212529;
  border: 1px solid rgba(0,0,0,0.1);
  border-radius: 6px;
  padding: 4px 6px;
  outline: none;
}

#doctorTable tfoot input:focus {
  border-color: #0d6efd; /* Bootstrap primary */
  box-shadow: 0 0 4px rgba(13,110,253,0.5);
}
  </style>
</head>
<body>

<div class="container">
  <div class="card p-4">
    <h3 class="text-center mb-4"></h3>

    <?php
      require("connect2.php");
      $sql = "SELECT Did, Dname, Demail,Dspecialization, Dphoneno FROM doctor";
      $result = mysqli_query($conn, $sql);
    ?>

    <div class="table-responsive">
      <table class="table table-bordered table-striped" id="doctorTable">
        <thead>
          <tr>
            <th style="background-color:black; color:white;">ID</th>
            <th style="background-color:black; color:white;">Name</th>
            <th style="background-color:black; color:white;">Email</th>
            <th style="background-color:black; color:white;">Specialization</th>
            <th style="background-color:black; color:white;">Phone</th>
            <th style="background-color:black; color:white;">Actions</th>
          </tr>
        </thead>
        <tfoot>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Specialization</th>
      <th>Phone</th>
      <th>Actions</th>

    </tr>
  </tfoot>
        <tbody>
          <?php
            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr data-id='{$row['Did']}'>
                        <td>{$row['Did']}</td>
                        <td class='editable' data-field='Dname'>{$row['Dname']}</td>
                        <td class='editable' data-field='Demail'>{$row['Demail']}</td>
                        <td class='editable' data-field='Dspecialization'>{$row['Dspecialization']}</td>
                        <td class='editable' data-field='Dphoneno'>{$row['Dphoneno']}</td>
                        <td class='action-buttons'>
                          <button class='btn btn-sm btn-warning editBtn'>Edit</button>
                          <button class='btn btn-sm btn-danger deleteBtn'>Delete</button>
                          <a href='doctorpdf_generation.php?Did={$row['Did']}' target='_blank' class='btn btn-sm btn-info'>Print PDF</a>

                        </td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='5'>No data found</td></tr>";
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
    $('#doctorTable tfoot th').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="Search '+title+'" />');
    });

    // Initialize DataTable
    var table = $('#doctorTable').DataTable();

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
    $('#doctorTable').DataTable();

    $(document).on('click', '.editBtn', function () {
      const btn = $(this);
      const row = btn.closest('tr');
      const isEditing = btn.text() === 'Save';

      if (!isEditing) {
        row.find('.editable').attr('contenteditable', 'true').addClass('editing');
        btn.text('Save');
      } else {
        const id = row.data('id');
        let data = { id: id };

        row.find('.editable').each(function () {
          const field = $(this).data('field');
          const value = $(this).text().trim();
          data[field] = value;
        });

        $.post('update_doctor.php', data, function (response) {
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

      $.post('delete_doctor.php', { id: id }, function (response) {
        alert(response);
        row.remove();
      });
    });
  });
</script>

</body>
</html>
