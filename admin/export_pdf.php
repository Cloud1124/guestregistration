<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once 'db_config.php';

// Custom settings
$company_name = "Christian J.H.A.I.S.O.N.";
$company_name2 = "Church of God in Christ";
$company_name3 = "(iglesia Cristiana), Inc.";
$company_address = "Caggay Chapter, Degala Street, Caggay, Tuguegarao City, Cagayan, Philippines";
$logo_path = "logo.png"; // Place your logo in the same directory or adjust path

// Define the MYPDF class BEFORE using it
class MYPDF extends TCPDF {
    public $logo_path;
    public $company_name;
    public $company_name2;
    public $company_name3;
    public $company_address;

    public function Header() {
        // Logo
        if (file_exists($this->logo_path)) {
            $this->Image($this->logo_path, 120, 8, 30, 0, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
        }
        // Company name and address
        $this->SetY(12);
        $this->SetFont('dejavusans', 'B', 14);
        $this->Cell(0, 7, $this->company_name, 0, 1, 'C', 0, '', 0);
        $this->SetFont('dejavusans', '', 10);
        $this->Cell(0, 7, $this->company_name2, 0, 1, 'C', 0, '', 0);
        $this->SetFont('dejavusans', '', 10);
        $this->Cell(0, 7, $this->company_name3, 0, 1, 'C', 0, '', 0);
        $this->SetFont('dejavusans', '', 10);
        $this->Cell(0, 7, $this->company_address, 0, 1, 'C', 0, '', 0);
        $this->Ln(2);
    }
}

// Now create the PDF object
$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->logo_path = $logo_path;
$pdf->company_name = $company_name;
$pdf->company_name2 = $company_name2;
$pdf->company_name3 = $company_name3;
$pdf->company_address = $company_address;

// Set document information
$pdf->SetCreator('GuestReg');
$pdf->SetAuthor($company_name);
$pdf->SetTitle('Registered Guests');
$pdf->SetSubject('Guest List Export');

// Set margins (left, top, right) AFTER creating the MYPDF object
$pdf->SetMargins(10, 40, 10); // 55 or higher for more space
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 10);

// Set font
$pdf->SetFont('dejavusans', '', 9);

// HEADER

// Add a page
$pdf->AddPage();

// Table header with autosize columns (remove width attributes)
$html = '<table border="1" cellpadding="3">
    <thead>
        <tr style="background-color:#f2f2f2;">
            <th>ID</th>
            <th>Name</th>
            <th>Agency/Org.</th>
            <th>Designation</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Gender</th>
            <th>Signature</th>
            <th>Registered At</th>
        </tr>
    </thead>
    <tbody>
';

// Fetch data
$query = $conn->query("SELECT id, name, agency_org, designation, email, mobile, gender, signature_data, registered_at FROM guests ORDER BY registered_at DESC");
while ($row = $query->fetch_assoc()) {
    // Format date
    $date = date('m-d-Y', strtotime($row['registered_at']));
    // Signature image (if present)
    $signature_img = '';
    $signature_file = $row['signature_data'];
    $signature_path = __DIR__ . '/signature/' . $signature_file;
    $default_signature = 'signature/default_signature.png'; // Make sure this file exists

    if (!empty($signature_file) && file_exists($signature_path) && @getimagesize($signature_path)) {
        $signature_img = '<img src="signature/' . htmlspecialchars($signature_file) . '" style="max-width:120px;max-height:60px;">';
    } else {
        $signature_img = '<img src="' . $default_signature . '" style="max-width:120px;max-height:60px;">';
    }
    $html .= '<tr>
        <td>' . htmlspecialchars($row['id']) . '</td>
        <td>' . htmlspecialchars($row['name']) . '</td>
        <td>' . htmlspecialchars($row['agency_org']) . '</td>
        <td>' . htmlspecialchars($row['designation']) . '</td>
        <td>' . htmlspecialchars($row['email']) . '</td>
        <td>' . htmlspecialchars($row['mobile']) . '</td>
        <td>' . htmlspecialchars($row['gender']) . '</td>
        <td align="center">' . $signature_img . '</td>
        <td>' . $date . '</td>
    </tr>';
}
$html .= '</tbody></table>';

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF
$pdf->Output('guests_export_' . date('Ymd_His') . '.pdf', 'D');
exit;
?>