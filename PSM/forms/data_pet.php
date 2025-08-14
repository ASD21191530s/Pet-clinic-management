<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pet Details</title>

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
      background-color: #f0f4f8;
    }
    .card {
      border-radius: 16px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    .table thead {
      background-color: #0d6efd;
      color: white;
    }
    td[contenteditable] {
      background-color: #fff3cd;
      border: 1px dashed #ffc107;
    }
    .action-buttons .btn {
      margin-right: 5px;
    }
   
  </style>
</head>
<body>

<div class="container">
  <div class="card p-4">
    <h3 class="text-center mb-4"></h3>

    <?php
      require("connect2.php");
      $sql = "SELECT Pid, Pname, Pbreed, Pspecies, Pgender, Oid FROM pet";
      $result = mysqli_query($conn, $sql);
    ?>

    <div class="table-responsive">
      <table class="table table-bordered table-striped" id="userTable">
        <thead>
          <tr>
            <th style="background-color:black; color:white;">ID</th>
            <th style="background-color:black; color:white;">Name</th>
            <th style="background-color:black; color:white;">Breed</th>
            <th style="background-color:black; color:white;">Species</th>
            <th style="background-color:black; color:white;">Gender</th>
            <th style="background-color:black; color:white;">Owner ID</th>
            <th style="background-color:black; color:white;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr data-id='{$row['Pid']}'>
                        <td>{$row['Pid']}</td>
                        <td class='editable' data-field='Pname'>{$row['Pname']}</td>
                        <td class='editable' data-field='Pbreed'>{$row['Pbreed']}</td>
                        <td class='editable' data-field='Pspecies'>{$row['Pspecies']}</td>
                        <td class='editable' data-field='Pgender'>{$row['Pgender']}</td>
                        <td class='editable' data-field='Oid'>{$row['Oid']}</td>
                        <td class='action-buttons'>
                          <button class='btn btn-sm btn-warning editBtn'>Edit</button>
                          <button class='btn btn-sm btn-danger deleteBtn'>Delete</button>
                        </td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='7'>No data found</td></tr>";
            }
            mysqli_close($conn);
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- jQuery AJAX Script -->
<script>
  $(document).ready(function(){
    $('#userTable').DataTable();

    // Toggle Edit/Save
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

        $.post('update_pet.php', data, function (response) {
          alert(response);
          row.find('.editable').removeAttr('contenteditable').removeClass('editing');
          btn.text('Edit');
        });
      }
    });

    // Delete row
    $(document).on('click', '.deleteBtn', function () {
      if (!confirm("Are you sure you want to delete this record?")) return;

      const row = $(this).closest('tr');
      const id = row.data('id');

      $.post('delete_pet.php', { id: id }, function (response) {
        alert(response);
        row.remove();
      });
    });
  });
</script>

</body>
</html>
