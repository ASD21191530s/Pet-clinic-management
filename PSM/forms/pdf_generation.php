<!--PDF GENERATION CODE-->
<?php
require('../fpdf182/fpdf.php'); // Ensure FPDF is installed and accessible

// Database connection
$db = new mysqli('localhost', 'root', '', 'pet clinic');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Get document ID
if (isset($_GET['document_id'])) {
    $document_id = intval($_GET['document_id']);
    $query = "SELECT * FROM documents WHERE document_id = $document_id";
    $result = $db->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Generate PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Document Details', 0, 1, 'C');

        $pdf->SetFont('Arial', '', 12);
        foreach ($row as $key => $value) {
            $pdf->Cell(50, 10, ucfirst(str_replace("_", " ", $key)) . ":", 0, 0);
            $pdf->Cell(0, 10, $value, 0, 1);
        }

        $pdf->Output();
    } else {
        echo "No document found with ID $document_id.";
    }
} else {
    echo "No document ID provided.";
}
?>