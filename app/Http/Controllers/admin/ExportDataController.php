<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportDataController extends Controller
{
    public function export_to_excel(Request $request)
    {
        $type = $request->input('type');

        switch ($type) {
            case 'so_quy':
                $this->export_so_quy($request);
                break;
            case 'nhom_quy':
                $this->export_nhom_quy($request);
                break;
            case 'loai_quy':
                $this->export_loai_quy($request);
                break;
            case 'nguyen_lieu_tho':
                $this->export_nguyen_lieu_tho($request);
                break;
            case 'nguyen_lieu_phan_loai':
                $this->export_nguyen_lieu_phan_loai($request);
                break;
            case 'nguyen_lieu_tinh':
                $this->export_nguyen_lieu_tinh($request);
                break;
            case 'phieu_san_xuat':
                $this->export_phieu_san_xuat($request);
                break;
            case 'nguyen_lieu_san_xuat':
                $this->export_nguyen_lieu_san_xuat($request);
                break;
            case 'nguyen_lieu_thanh_pham':
                $this->export_nguyen_lieu_thanh_pham($request);
                break;
            case 'san_pham':
                $this->export_san_pham($request);
                break;
            case 'ban_hang':
                $this->export_ban_hang($request);
                break;
            case 'nha_cung_cap':
                $this->export_nha_cung_cap($request);
                break;
            case 'khach_hang':
                $this->export_khach_hang($request);
                break;
            case 'nhom_khach_hang':
                $this->export_nhom_khach_hang($request);
                break;
            default:
                break;
        }
    }

    private function export_so_quy(Request $request)
    {
        try {
            $spreadsheet = new Spreadsheet();
        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_nhom_quy(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_loai_quy(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_nguyen_lieu_tho(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_nguyen_lieu_phan_loai(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_nguyen_lieu_tinh(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_phieu_san_xuat(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_nguyen_lieu_san_xuat(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_nguyen_lieu_thanh_pham(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_san_pham(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_ban_hang(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_nha_cung_cap(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_khach_hang(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function export_nhom_khach_hang(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            $data = returnMessage(-1, null, $ex->getMessage());
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function render_sheet(Spreadsheet $spreadsheet, array $headers, array $result, string $title, callable $mapRow = null)
    {
        $lastColumn = Coordinate::stringFromColumnIndex(count($headers));

        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Tahoma')->setSize(14);

        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->setCellValue('A1', $title);
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'BDD6EE']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(40);

        $sheet->fromArray($headers, null, 'A2');
        $sheet->getStyle("A2:{$lastColumn}2")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D8D8D8']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(22);

        $row = 3;
        foreach ($result as $index => $item) {
            $data = $mapRow ? $mapRow($item, $index) : (array)$item;
            $sheet->fromArray($data, null, 'A' . $row);
            $row++;
        }

        $lastIndex = Coordinate::columnIndexFromString($lastColumn);
        for ($col = 1; $col <= $lastIndex; $col++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
        }

        if ($row > 3) {
            $dataRange = "A3:{$lastColumn}" . ($row - 1);
            $sheet->getStyle($dataRange)->applyFromArray([
                'font' => ['size' => 11],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
        }

        return $sheet;
    }
}
