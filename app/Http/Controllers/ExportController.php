<?php

namespace App\Http\Controllers;

use App\Exports\SalesExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;  //paquete facade para reporte pdf
use Carbon\Carbon;  //paquete para calendario personalizado
use App\Models\Sale;
use App\Models\Income;
use App\Models\Transfer;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;    //paquete facade para reporte excel

class ExportController extends Controller
{
    public function reportPDF($userId,$reportRange,$reportType,$dateFrom = null,$dateTo = null){  //metodo para reporte pdf

        $data = [];

        if($reportRange == 0){     //validar si el usuario esta seleccionando/deja por defecto el tipo de reporte (ventas del dia)

            $from = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 00:00:00';    //dar formato personalizado a fecha de inicio y guardar en variable
            $to = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 23:59:59';  //dar formato personalizado a fecha de fin y guardar en variable

        }else{

            $from = Carbon::parse($dateFrom)->format('Y-m-d') . ' 00:00:00';    //dar formato personalizado a fecha de inicio y guardar en variable
            $to = Carbon::parse($dateTo)->format('Y-m-d') . ' 23:59:59';    //dar formato personalizado a fecha de fin y guardar en variable

        }

        if($userId == 0 && $reportType == 0){     //validar si no se esta seleccionando un usuario (opcion todos)

            $data = Sale::join('users as u', 'u.id', 'sales.user_id')  //union de tabla ventas con tabla usuarios
            ->join('sale_details as s_d','s_d.sale_id','sales.id')
            ->join('products as p','p.id','s_d.product_id')
            ->select('sales.*','p.code as product','u.name as user','s_d.utility as utility')   //obtener todas las columnas de la tabla ventas y la columna name de la tabla users
            ->where('sales.status','pagado')
            ->whereBetween('sales.created_at', [$from, $to])    //filtrado de las ventas entre fechas
            ->get();    //obtener registros

            //validar si el usuario esta seleccionando/deja por defecto el usuario (todos) y guardar en variable
            $user = $userId == 0 ? 'Todos' : User::find($userId)->name;
            //metodo loadView de paquete DomPDF recibe entre parametros (vista, informacion a renderizar)
            $pdf = PDF::loadView('pdf.reporte', compact('data','user','reportRange','reportType','dateFrom','dateTo'));   //cargar vista y guardar en variable

            //metodo stream de paquete DomPDF
            return $pdf->stream('reporte.pdf'); //visualizar pdf
            //metodo download de paquete DomPDF
            //return $pdf->download('reporte.pdf');   //descargar pdf
        }

        if($userId != 0 && $reportType == 0){

            $data = Sale::join('users as u', 'u.id', 'sales.user_id')  //union de tabla ventas con tabla usuarios
            ->join('sale_details as s_d','s_d.sale_id','sales.id')
            ->join('products as p','p.id','s_d.product_id')
            ->select('sales.*','p.code as product','u.name as user','s_d.utility as utility')   //obtener todas las columnas de la tabla ventas y la columna name de la tabla users
            ->whereBetween('sales.created_at', [$from, $to])    //filtrado de las ventas entre fechas
            ->where('sales.status','pagado')
            ->where('user_id', $userId)   //filtrado de las ventas del usuario seleccionado
            ->get();    //obtener registros

            //validar si el usuario esta seleccionando/deja por defecto el usuario (todos) y guardar en variable
            $user = $userId == 0 ? 'Todos' : User::find($userId)->name;
            //metodo loadView de paquete DomPDF recibe entre parametros (vista, informacion a renderizar)
            $pdf = PDF::loadView('pdf.reporte', compact('data','user','reportRange','reportType','dateFrom','dateTo'));   //cargar vista y guardar en variable

            //metodo stream de paquete DomPDF
            return $pdf->stream('reporte.pdf'); //visualizar pdf
            //metodo download de paquete DomPDF
            //return $pdf->download('reporte.pdf');   //descargar pdf
        }

        if($userId == 0 && $reportType == 1){

            $data = Income::join('users as u', 'u.id', 'incomes.user_id')  //union de tabla ventas con tabla usuarios
            ->join('income_details as i_d','i_d.income_id','incomes.id')
            ->join('products as p','p.id','i_d.product_id')
            ->select('incomes.*','p.code as product','u.name as user')   //obtener todas las columnas de la tabla ventas y la columna name de la tabla users
            ->where('incomes.status','recibido')
            ->whereBetween('incomes.created_at', [$from, $to])    //filtrado de las ventas entre fechas
            ->get();    //obtener registros

            //validar si el usuario esta seleccionando/deja por defecto el usuario (todos) y guardar en variable
            $user = $userId == 0 ? 'Todos' : User::find($userId)->name;
            //metodo loadView de paquete DomPDF recibe entre parametros (vista, informacion a renderizar)
            $pdf = PDF::loadView('pdf.reporte', compact('data','user','reportRange','reportType','dateFrom','dateTo'));   //cargar vista y guardar en variable

            //metodo stream de paquete DomPDF
            return $pdf->stream('reporte.pdf'); //visualizar pdf
            //metodo download de paquete DomPDF
            //return $pdf->download('reporte.pdf');   //descargar pdf
        }

        if($userId != 0 && $reportType == 1){

            $data = Income::join('users as u', 'u.id', 'incomes.user_id')  //union de tabla ventas con tabla usuarios
            ->join('income_details as i_d','i_d.income_id','incomes.id')
            ->join('products as p','p.id','i_d.product_id')
            ->select('incomes.*','p.code as product','u.name as user')   //obtener todas las columnas de la tabla ventas y la columna name de la tabla users
            ->whereBetween('incomes.created_at', [$from, $to])    //filtrado de las ventas entre fechas
            ->where('incomes.status','recibido')
            ->where('user_id', $userId)   //filtrado de las ventas del usuario seleccionado
            ->get();    //obtener registros

            //validar si el usuario esta seleccionando/deja por defecto el usuario (todos) y guardar en variable
            $user = $userId == 0 ? 'Todos' : User::find($userId)->name;
            //metodo loadView de paquete DomPDF recibe entre parametros (vista, informacion a renderizar)
            $pdf = PDF::loadView('pdf.reporte', compact('data','user','reportRange','reportType','dateFrom','dateTo'));   //cargar vista y guardar en variable

            //metodo stream de paquete DomPDF
            return $pdf->stream('reporte.pdf'); //visualizar pdf
            //metodo download de paquete DomPDF
            //return $pdf->download('reporte.pdf');   //descargar pdf
        }

        if($userId == 0 && $reportType == 2){

            $data = Transfer::join('users as u', 'u.id', 'transfers.user_id')  //union de tabla ventas con tabla usuarios
            ->join('transfer_details as t_d','t_d.transfer_id','transfers.id')
            ->join('products as p','p.id','t_d.product_id')
            ->select('transfers.*','p.code as product','u.name as user')   //obtener todas las columnas de la tabla ventas y la columna name de la tabla users
            ->where('transfers.status','recibido')
            ->whereBetween('transfers.created_at', [$from, $to])    //filtrado de las ventas entre fechas
            ->get();    //obtener registros

            //validar si el usuario esta seleccionando/deja por defecto el usuario (todos) y guardar en variable
            $user = $userId == 0 ? 'Todos' : User::find($userId)->name;
            //metodo loadView de paquete DomPDF recibe entre parametros (vista, informacion a renderizar)
            $pdf = PDF::loadView('pdf.reporte', compact('data','user','reportRange','reportType','dateFrom','dateTo'));   //cargar vista y guardar en variable

            //metodo stream de paquete DomPDF
            return $pdf->stream('reporte.pdf'); //visualizar pdf
            //metodo download de paquete DomPDF
            //return $pdf->download('reporte.pdf');   //descargar pdf
        }

        if($userId != 0 && $reportType == 2){

            $data = Transfer::join('users as u', 'u.id', 'transfers.user_id')  //union de tabla ventas con tabla usuarios
            ->join('transfer_details as t_d','t_d.transfer_id','transfers.id')
            ->join('products as p','p.id','t_d.product_id')
            ->select('transfers.*','p.code as product','u.name as user')   //obtener todas las columnas de la tabla ventas y la columna name de la tabla users
            ->whereBetween('transfers.created_at', [$from, $to])    //filtrado de las ventas entre fechas
            ->where('transfers.status','recibido')
            ->where('user_id', $userId)   //filtrado de las ventas del usuario seleccionado
            ->get();    //obtener registros

            //validar si el usuario esta seleccionando/deja por defecto el usuario (todos) y guardar en variable
            $user = $userId == 0 ? 'Todos' : User::find($userId)->name;
            //metodo loadView de paquete DomPDF recibe entre parametros (vista, informacion a renderizar)
            $pdf = PDF::loadView('pdf.reporte', compact('data','user','reportRange','reportType','dateFrom','dateTo'));   //cargar vista y guardar en variable

            //metodo stream de paquete DomPDF
            return $pdf->stream('reporte.pdf'); //visualizar pdf
            //metodo download de paquete DomPDF
            //return $pdf->download('reporte.pdf');   //descargar pdf
        }

    }

    public function reporteExcel($userId,$reportRange,$reportType,$dateFrom = null,$dateTo = null){

        $reportName = 'Reporte_' . uniqid() . '.xlsx';
        return Excel::download(new SalesExport($userId,$reportRange,$reportType,$dateFrom,$dateTo),$reportName );
    }
}
