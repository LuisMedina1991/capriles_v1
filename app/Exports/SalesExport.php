<?php

namespace App\Exports;

use App\Models\Income;
use App\Models\Sale;
use App\Models\Transfer;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;  //para trabajar con colecciones y obtener la data
use Maatwebsite\Excel\Concerns\WithHeadings;    //para definir los titulos del encabezado del reporte
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;   //para interactuar con el libro de excel
use Maatwebsite\Excel\Concerns\WithCustomStartCell; //para definir la celda donde inicia el reporte
use Maatwebsite\Excel\Concerns\WithTitle;   //para colocar nombre a las hojas del libro
use Maatwebsite\Excel\Concerns\WithStyles;  //para dar formato a las celdas
use Maatwebsite\Excel\Concerns\ShouldAutoSize;  //se calcula un ancho automatico para las columnas
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class SalesExport implements FromCollection,WithHeadings,WithCustomStartCell,WithTitle,WithStyles,ShouldAutoSize,WithColumnWidths
{

    //propiedades protegidas
    protected $userId,$dateFrom,$dateTo,$reportRange,$reportType;

    public function __construct($userId,$reportRange,$reportType,$f1,$f2)
    {
        $this->userId = $userId;
        $this->reportRange = $reportRange;
        $this->reportType = $reportType;
        $this->dateFrom = $f1;
        $this->dateTo = $f2;
    }

    public function collection()
    {
        $data = [];

        if($this->reportRange == 1){ //validar si el usuario esta seleccionando ventas por fecha

            $from = Carbon::parse($this->dateFrom)->format('Y-m-d') . ' 00:00:00';  //dar formato personalizado a fecha de inicio y guardar en variable
            $to = Carbon::parse($this->dateTo)->format('Y-m-d') . ' 23:59:59';  //dar formato personalizado a fecha final y guardar en variable
        }else{

            $from = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 00:00:00';    //dar formato personalizado a fecha de inicio y guardar en variable
            $to = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 23:59:59';  //dar formato personalizado a fecha final y guardar en variable
        }

        if($this->userId == 0 && $this->reportType == 0){     //validar si no se esta seleccionando un usuario (opcion todos)

            $data = Sale::join('users as u', 'u.id', 'sales.user_id')  //union de tabla ventas con tabla usuarios
            ->join('sale_details as s_d','s_d.sale_id','sales.id')
            ->join('products as p','p.id','s_d.product_id')
            ->select('p.code as product','sales.total','sales.items','sales.status','u.name as user','sales.created_at')   //obtener todas las columnas de la tabla ventas y la columna name de la tabla users
            ->whereBetween('sales.created_at', [$from, $to])    //filtrado de las ventas entre fechas
            ->get();    //obtener registros

            return $data;
        }

        if($this->userId != 0 && $this->reportType == 0){

            $data = Sale::join('users as u', 'u.id', 'sales.user_id')  //union de tabla ventas con tabla usuarios
            ->join('sale_details as s_d','s_d.sale_id','sales.id')
            ->join('products as p','p.id','s_d.product_id')
            ->select('p.code as product','sales.total','sales.items','sales.status','u.name as user','sales.created_at')   //obtener todas las columnas de la tabla ventas y la columna name de la tabla users
            ->whereBetween('sales.created_at', [$from, $to])    //filtrado de las ventas entre fechas
            ->where('user_id', $this->userId)   //filtrado de las ventas del usuario seleccionado
            ->get();    //obtener registros

            //return $data;
        }

        if($this->userId == 0 && $this->reportType == 1){     //validar si no se esta seleccionando un usuario (opcion todos)

            $data = Income::join('users as u', 'u.id', 'incomes.user_id')  //union de tabla ventas con tabla usuarios
            ->join('income_details as i_d','i_d.income_id','incomes.id')
            ->join('products as p','p.id','i_d.product_id')
            ->select('p.code as product','incomes.total','incomes.items','incomes.status','u.name as user','incomes.created_at')   //obtener todas las columnas de la tabla ventas y la columna name de la tabla users
            ->whereBetween('incomes.created_at', [$from, $to])    //filtrado de las ventas entre fechas
            ->get();    //obtener registros

            //return $data;
        }

        if($this->userId != 0 && $this->reportType == 1){

            $data = Income::join('users as u', 'u.id', 'incomes.user_id')  //union de tabla ventas con tabla usuarios
            ->join('income_details as i_d','i_d.income_id','incomes.id')
            ->join('products as p','p.id','i_d.product_id')
            ->select('p.code as product','incomes.total','incomes.items','incomes.status','u.name as user','incomes.created_at')   //obtener todas las columnas de la tabla ventas y la columna name de la tabla users
            ->whereBetween('incomes.created_at', [$from, $to])    //filtrado de las ventas entre fechas
            ->where('user_id', $this->userId)   //filtrado de las ventas del usuario seleccionado
            ->get();    //obtener registros

            //return $data;
        }

        if($this->userId == 0 && $this->reportType == 2){     //validar si no se esta seleccionando un usuario (opcion todos)

            $data = Transfer::join('users as u', 'u.id', 'transfers.user_id')  //union de tabla ventas con tabla usuarios
            ->join('transfer_details as t_d','t_d.transfer_id','transfers.id')
            ->join('products as p','p.id','t_d.product_id')
            ->select('p.code as product','transfers.total','transfers.items','transfers.status','u.name as user','transfers.created_at')   //obtener todas las columnas de la tabla ventas y la columna name de la tabla users
            ->whereBetween('transfers.created_at', [$from, $to])    //filtrado de las ventas entre fechas
            ->get();    //obtener registros

            //return $data;
        }

        if($this->userId != 0 && $this->reportType == 2){

            $data = Transfer::join('users as u', 'u.id', 'transfers.user_id')  //union de tabla ventas con tabla usuarios
            ->join('transfer_details as t_d','t_d.transfer_id','transfers.id')
            ->join('products as p','p.id','t_d.product_id')
            ->select('p.code as product','transfers.total','transfers.items','transfers.status','u.name as user','transfers.created_at')   //obtener todas las columnas de la tabla ventas y la columna name de la tabla users
            ->whereBetween('transfers.created_at', [$from, $to])    //filtrado de las ventas entre fechas
            ->where('user_id', $this->userId)   //filtrado de las ventas del usuario seleccionado
            ->get();    //obtener registros

            //return $data;
        }

        return $data;
    }

    
    public function headings(): array   //cabeceras del reporte
    {
        return ["PRODUCTO", "TOTAL", "CANTIDAD", "ESTADO", "USUARIO", "FECHA"];
    }

    public function startCell(): string
    {
        return 'B2';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            2 => ['font' => ['bold' => true,'size' => 12]],
        ];
    }

    public function title(): string
    {
        return 'Reportes';
    }

    public function columnWidths(): array
    {
        return [
            'B' => 13,
            'C' => 8,
            'E' => 10,            
        ];
    }

}
