<?php

use FPDF\FPDF;

class ExportController
{
    private $roomModel;

    public function __construct()
    {
        require_once __DIR__ . '/../models/Room.php';
        $this->roomModel = new Room();
    }

    public function csv()
    {
        requireAdmin();

        $rooms = $this->roomModel->getAll();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=rooms_export_' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');

        // CSV Header
        fputcsv($output, ['ID', 'Room Number', 'Room Type', 'Capacity', 'Price', 'Status', 'Description']);

        foreach ($rooms as $room) {
            fputcsv($output, [
                $room['id'],
                $room['room_number'],
                ucfirst($room['room_type']),
                $room['capacity'],
                $room['price'],
                $room['is_available'] ? 'Available' : 'Unavailable',
                $room['description']
            ]);
        }

        fclose($output);
        exit;
    }

    public function pdf()
    {
        requireAdmin();

        $rooms = $this->roomModel->getAll();

        // Create PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Hotel Rooms Report', 0, 1, 'C');
        $pdf->Ln(10);

        // Table Header
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(30, 10, 'Room #', 1, 0, 'C', true);
        $pdf->Cell(40, 10, 'Type', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Capacity', 1, 0, 'C', true);
        $pdf->Cell(40, 10, 'Price', 1, 0, 'C', true);
        $pdf->Cell(50, 10, 'Status', 1, 1, 'C', true);

        // Table Body
        $pdf->SetFont('Arial', '', 12);
        foreach ($rooms as $room) {
            $pdf->Cell(30, 10, $room['room_number'], 1);
            $pdf->Cell(40, 10, ucfirst($room['room_type']), 1);
            $pdf->Cell(30, 10, $room['capacity'], 1, 0, 'C');
            $pdf->Cell(40, 10, '$' . number_format($room['price'], 2), 1, 0, 'R');
            $pdf->Cell(50, 10, $room['is_available'] ? 'Available' : 'Unavailable', 1, 1, 'C');
        }

        $pdf->Output('D', 'rooms_report_' . date('Y-m-d') . '.pdf');
        exit;
    }
}
