<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget">
            <div class="widget-heading">
                <h4 class="card-title text-center"><b>{{$componentName}}</b></h4>
                {{--@switch($reportType)
                @case(1)
                <h5>TOTAL DE INGRESOS: ${{number_format($incomes->sum('total'), 2)}}</h5>
                @break
                @case(3)
                <h5>TOTAL DE VENTAS: ${{number_format($sales->sum('total'), 2)}}</h5>
                <h5>TOTAL DE UTILIDAD: ${{number_format($sales->sum('utility'), 2)}}</h5>
                @break
                @endswitch--}}
            </div>
            <div class="widget-content">
                <div class="row">
                    <div class="col-sm-12 col-md-3">
                        <div class="col">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    @switch($reportType)
                                    @case(1)
                                    <h5>TOTAL DE INGRESOS: ${{number_format($incomes->sum('total'), 2)}}</h5>
                                    <h5>TOTAL DE IMPUESTOS: ${{number_format($incomes->sum('tax.amount'), 2)}}</h5>
                                    @break
                                    @case(3)
                                    <h5>TOTAL DE VENTAS: ${{number_format($sales->sum('total_cost'), 2)}}</h5>
                                    <h5>TOTAL DE UTILIDAD: ${{number_format($sales->sum('utility'), 2)}}</h5>
                                    <h5>TOTAL DE IMPUESTOS: ${{number_format($sales->sum('tax.amount'), 2)}}</h5>
                                    @break
                                    @endswitch
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text input-gp">
                                            <i class="fas fa-search"></i>
                                        </span>
                                    </div>
                                    <input type="text" wire:model="search" placeholder="Buscar codigo..."
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <h6>Estado del movimiento</h6>
                                <div class="form-group">
                                    <select wire:model="reportStatus" class="form-control">
                                        <option value="0">Vigente</option>
                                        <option value="1">Anulado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <h6>Elige el usuario</h6>
                                <div class="form-group">
                                    <select wire:model="userId" class="form-control">
                                        <option value="0">Todos</option>
                                        @foreach ($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <h6>Elige el tipo de reporte</h6>
                                <div class="form-group">
                                    <select wire:model="reportType" class="form-control">
                                        <option value="0">Reporte General</option>
                                        <option value="1">Reporte de Ingresos</option>
                                        <option value="2">Reporte de Traspasos</option>
                                        <option value="3">Reporte de Ventas</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <h6>Elige el alcance del reporte</h6>
                                <div class="form-group">
                                    <select wire:model="reportRange" class="form-control">
                                        <option value="0">Reportes del dia</option>
                                        <option value="1">Reportes por fecha</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 mt-2">
                                <h6>Fecha inicial</h6>
                                <div class="form-group">
                                    <input type="text" wire:model="dateFrom" class="form-control flatpickr"
                                        placeholder="Click para elegir">
                                </div>
                            </div>
                            <div class="col-sm-12 mt-2">
                                <h6>Fecha final</h6>
                                <div class="form-group">
                                    <input type="text" wire:model="dateTo" class="form-control flatpickr"
                                        placeholder="Click para elegir">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button wire:click="$refresh" class="btn btn-dark btn-block">
                                    Consultar
                                </button>
                                @if($reportType == 0)
                                <a href="{{ url('warehouse_reports/pdf' . '/' . $userId . '/' . $reportRange . '/' . $reportType . '/' . $reportStatus . '/' . $dateFrom . '/' . $dateTo . '/' . $search) }}"
                                    class="btn btn-dark btn-block {{(count($incomes) + count($transfers) + count($sales)) < 1 ? 'disabled' : ''}}"
                                    target="_blank">
                                    Generar PDF
                                </a>
                                @endif
                                @if($reportType == 1)
                                <a href="{{ url('warehouse_reports/pdf' . '/' . $userId . '/' . $reportRange . '/' . $reportType . '/' . $reportStatus . '/' . $dateFrom . '/' . $dateTo . '/' . $search) }}"
                                    class="btn btn-dark btn-block {{count($incomes) < 1 ? 'disabled' : ''}}"
                                    target="_blank">
                                    Generar PDF
                                </a>
                                @endif
                                @if($reportType == 2)
                                <a href="{{ url('warehouse_reports/pdf' . '/' . $userId . '/' . $reportRange . '/' . $reportType . '/' . $reportStatus . '/' . $dateFrom . '/' . $dateTo . '/' . $search) }}"
                                    class="btn btn-dark btn-block {{count($transfers) < 1 ? 'disabled' : ''}}"
                                    target="_blank">
                                    Generar PDF
                                </a>
                                @endif
                                @if($reportType == 3)
                                <a href="{{ url('warehouse_reports/pdf' . '/' . $userId . '/' . $reportRange . '/' . $reportType . '/' . $reportStatus . '/' . $dateFrom . '/' . $dateTo . '/' . $search) }}"
                                    class="btn btn-dark btn-block {{count($sales) < 1 ? 'disabled' : ''}}"
                                    target="_blank">
                                    Generar PDF
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-9">
                        <div class="table-responsive-xxl">
                            <table class="table table-sm table-striped table-bordered mt-1">
                                <thead class="text-white" style="background: #3B3F5C">
                                    <tr>
                                        @if($reportRange != 0)
                                        <th class="table-th text-white text-center">fecha</th>
                                        @endif
                                        <th class="table-th text-white text-center">n° recibo</th>
                                        <th class="table-th text-white text-center">producto</th>
                                        <th class="table-th text-white text-center">estado</th>
                                        <th class="table-th text-white text-center">marca</th>
                                        <th class="table-th text-white text-center">cant</th>
                                        <th class="table-th text-white text-center">costo</th>
                                        @switch($reportType)
                                        @case (0)
                                        <th class="table-th text-white text-center">total</th>
                                        <th class="table-th text-white text-center">sucursal</th>
                                        @break
                                        @case(1)
                                        <th class="table-th text-white text-center">total</th>
                                        <th class="table-th text-white text-center">impuesto</th>
                                        <th class="table-th text-white text-center">proveedor</th>
                                        <th class="table-th text-white text-center">sucursal</th>
                                        <th class="table-th text-white text-center">tipo de ingreso</th>
                                        <th class="table-th text-white text-center">tipo de pago</th>
                                        @break
                                        @case(2)
                                        <th class="table-th text-white text-center">origen</th>
                                        <th class="table-th text-white text-center">destino</th>
                                        @break
                                        @case(3)
                                        <th class="table-th text-white text-center">total</th>
                                        <th class="table-th text-white text-center">impuesto</th>
                                        <th class="table-th text-white text-center">utilidad</th>
                                        <th class="table-th text-white text-center">sucursal</th>
                                        <th class="table-th text-white text-center">cliente</th>
                                        <th class="table-th text-white text-center">tipo de pago</th>
                                        @break
                                        @endswitch
                                        {{--@if($userId == 0)
                                        <th class="table-th text-white text-center">usuario</th>
                                        @endif--}}
                                        @if($reportType != 0 && $reportStatus == 0 && $reportRange == 0)
                                        <th class="table-th text-white text-center">opciones</th>
                                        @else
                                        <th class="table-th text-white text-center">tipo</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($reportRange == 1 && ($dateFrom == '' || $dateTo == ''))
                                    <tr>
                                        <td colspan="18">
                                            <h6 class="text-center text-muted">Sin resultados</h6>
                                        </td>
                                    </tr>
                                    @else
                                    @if($reportType == 0 || $reportType == 1)
                                    @foreach ($incomes as $income)
                                    <tr>
                                        @if($reportRange != 0)
                                        <td class="text-center">
                                            <h6>{{Carbon\Carbon::parse($income->created_at)->format('d-m-Y')}}</h6>
                                        </td>
                                        @endif
                                        <td class="text-center">
                                            <h6>{{$income->file_number}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{$income->stock->value->product->code}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>
                                                @switch($income->status->name)
                                                @case('realizado')
                                                <span class="text-uppercase badge badge-success">
                                                    {{$income->status->name}}
                                                </span>
                                                @break
                                                @case('pendiente')
                                                <span class="text-uppercase badge badge-warning">
                                                    {{$income->status->name}}
                                                </span>
                                                @break
                                                @case('anulado')
                                                <span class="text-uppercase badge badge-danger">
                                                    {{$income->status->name}}
                                                </span>
                                                @break
                                                @endswitch
                                            </h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{$income->stock->value->product->brand->name}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{$income->quantity}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>${{number_format($income->stock->value->cost,2)}}</h6>
                                        </td>
                                        @if($reportType == 0)
                                        <td class="text-center">
                                            <h6>${{number_format($income->total,2)}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{$income->stock->office->name}}</h6>
                                        </td>
                                        @else
                                        <td class="text-center">
                                            <h6>${{number_format($income->total,2)}}</h6>
                                        </td>
                                        <td class="text-center">
                                            @if($income->tax)
                                            <h6>${{number_format($income->tax->amount,2)}}</h6>
                                            @else
                                            <h6></h6>
                                            @endif
                                        </td>
                                        @if($income->income_type == 'compra')
                                        <td class="text-center">
                                            <h6>{{$income->supplier->name}}</h6>
                                        </td>
                                        @else
                                        <td class="text-center">
                                            <h6>{{$income->customer->name}}</h6>
                                        </td>
                                        @endif
                                        <td class="text-center">
                                            <h6>{{$income->stock->office->name}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{$income->income_type}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{$income->payment_type}}</h6>
                                        </td>
                                        @endif
                                        {{--@if($userId == 0)
                                        <td class="text-center">
                                            <h6>{{$income->user->name}}</h6>
                                        </td>
                                        @endif--}}
                                        @if($reportType != 0 && $reportStatus == 0 && $reportRange == 0)
                                        <td class="text-center">
                                            <a href="javascript:void(0)" onclick="Confirm_1('{{$income->id}}')" class="btn btn-dark" title="Anular">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                        @else
                                        <td class="text-center">
                                            <h6>ingreso</h6>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                    @endif
                                    @if($reportType == 0 || $reportType == 2)
                                    @foreach ($transfers as $transfer)
                                    <tr>
                                        @if($reportRange != 0)
                                        <td class="text-center">
                                            <h6>{{Carbon\Carbon::parse($transfer->created_at)->format('d-m-Y')}}</h6>
                                        </td>
                                        @endif
                                        <td class="text-center">
                                            <h6>{{$transfer->file_number}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{$transfer->stock->value->product->code}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>
                                                @switch($transfer->status->name)
                                                @case('realizado')
                                                <span class="text-uppercase badge badge-success">
                                                    {{$transfer->status->name}}
                                                </span>
                                                @break
                                                @case('pendiente')
                                                <span class="text-uppercase badge badge-caution">
                                                    {{$transfer->status->name}}
                                                </span>
                                                @break
                                                @case('anulado')
                                                <span class="text-uppercase badge badge-danger">
                                                    {{$transfer->status->name}}
                                                </span>
                                                @break
                                                @endswitch
                                            </h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{$transfer->stock->value->product->brand->name}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{$transfer->quantity}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>${{number_format($transfer->stock->value->cost,2)}}</h6>
                                        </td>
                                        @if($reportType == 2)
                                        <td class="text-center">
                                            <h6>{{$transfer->from_office}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{$transfer->to_office}}</h6>
                                        </td>
                                        @else
                                        <td class="text-center">
                                            <h6></h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{$transfer->to_office}}</h6>
                                        </td>
                                        @endif
                                        {{--@if($userId == 0)
                                        <td class="text-center">
                                            <h6>{{$transfer->user->name}}</h6>
                                        </td>
                                        @endif--}}
                                        @if($reportType != 0 && $reportStatus == 0 && $reportRange == 0)
                                        <td class="text-center">
                                            <a href="javascript:void(0)" onclick="Confirm_2('{{$transfer->id}}')" class="btn btn-dark" title="Anular">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                        @else
                                        <td class="text-center">
                                            <h6>traspaso</h6>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                    @endif
                                    @if($reportType == 0 || $reportType == 3)
                                    @foreach ($sales as $sale)
                                    <tr>
                                        @if($reportRange != 0)
                                        <td class="text-center">
                                            <h6>{{Carbon\Carbon::parse($sale->created_at)->format('d-m-Y')}}</h6>
                                        </td>
                                        @endif
                                        <td class="text-center">
                                            <h6>{{$sale->file_number}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{$sale->stock->value->product->code}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>
                                                @switch($sale->status->name)
                                                @case('realizado')
                                                <span class="text-uppercase badge badge-success">
                                                    {{$sale->status->name}}
                                                </span>
                                                @break
                                                @case('pendiente')
                                                <span class="text-uppercase badge badge-warning">
                                                    {{$sale->status->name}}
                                                </span>
                                                @break
                                                @case('anulado')
                                                <span class="text-uppercase badge badge-danger">
                                                    {{$sale->status->name}}
                                                </span>
                                                @break
                                                @endswitch
                                            </h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{$sale->stock->value->product->brand->name}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{$sale->quantity}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>${{number_format($sale->stock->value->cost,2)}}</h6>
                                        </td>
                                        @if($reportType == 0)
                                        <td class="text-center">
                                            <h6>${{number_format($sale->total_cost,2)}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{$sale->stock->office->name}}</h6>
                                        </td>
                                        @else
                                        <td class="text-center">
                                            <h6>${{number_format($sale->total_cost,2)}}</h6>
                                        </td>
                                        <td class="text-center">
                                            @if($sale->tax)
                                            <h6>${{number_format($sale->tax->amount,2)}}</h6>
                                            @else
                                            <h6></h6>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <h6>${{number_format($sale->utility,2)}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{$sale->stock->office->name}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{$sale->customer->name}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{$sale->payment_type}}</h6>
                                        </td>
                                        @endif
                                        {{--@if($userId == 0)
                                        <td class="text-center">
                                            <h6>{{$sale->user->name}}</h6>
                                        </td>
                                        @endif--}}
                                        @if($reportType != 0 && $reportStatus == 0 && $reportRange == 0)
                                        <td class="text-center">
                                            <a href="javascript:void(0)" onclick="Confirm_3('{{$sale->id}}')" class="btn btn-dark" title="Anular">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                        @else
                                        <td class="text-center">
                                            <h6>venta</h6>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                    @endif
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{--@if ($reportType == 1)
                    <div class="col-sm-12 col-md-9">
                        <div class="table-responsive-xxl">
                            <table class="table table-sm table-striped table-bordered mt-1">
                                <thead class="text-white" style="background: #3B3F5C">
                                    <tr>
                                        <th class="table-th text-white text-center">PRODUCTO</th>
                                        <th class="table-th text-white text-center">MARCA</th>
                                        <th class="table-th text-white text-center">COSTO</th>
                                        <th class="table-th text-white text-center">N° RECIBO</th>
                                        <th class="table-th text-white text-center">CANT</th>
                                        <th class="table-th text-white text-center">TOTAL</th>
                                        <th class="table-th text-white text-center">SUCURSAL</th>
                                        <th class="table-th text-white text-center">TIPO DE INGRESO</th>
                                        <th class="table-th text-white text-center">USUARIO</th>
                                        <th class="table-th text-white text-center">FECHA</th>
                                        <th class="table-th text-white text-center">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if($reportRange == 1 && ($dateFrom == '' || $dateTo == ''))
                                    <tr>
                                        <td colspan="10">
                                            <h6 class="text-center text-muted">Sin resultados</h6>
                                        </td>
                                    </tr>
                                    @else

                                    @foreach ($income as $i)

                                    <tr>
                                        <td class="text-center">
                                            <h6>{{ $i->product->code }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ $i->product->brand }} | {{$i->product->threshing}} |
                                                {{$i->product->tarp}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>${{ $i->product->cost }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ $i->pf }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ $i->quantity }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>${{ number_format($i->total,2) }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ $i->office }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ $i->type }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ $i->user }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{Carbon\Carbon::parse($i->created_at)->format('d-m-Y')}}</h6>
                                        </td>
                                        @if(Carbon\Carbon::parse($i->created_at)->format('d-m-Y') ==
                                        Carbon\Carbon::today()->format('d-m-Y'))
                                        @can('cancelar_ingreso')
                                        <td class="text-center">
                                            <a href="javascript:void(0)" onclick="Confirm_1('{{$i->id}}')"
                                                class="btn btn-dark" title="Anular">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                        @endcan
                                        @endif
                                    </tr>

                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    @if ($reportType == 2)
                    <div class="col-sm-12 col-md-9">
                        <div class="table-responsive-xxl">
                            <table class="table table-sm table-striped table-bordered mt-1">
                                <thead class="text-white" style="background: #3B3F5C">
                                    <tr>
                                        <th class="table-th text-white text-center">PRODUCTO</th>
                                        <th class="table-th text-white text-center">MARCA</th>
                                        <th class="table-th text-white text-center">COSTO</th>
                                        <th class="table-th text-white text-center">N° RECIBO</th>
                                        <th class="table-th text-white text-center">CANT</th>
                                        <th class="table-th text-white text-center">TOTAL</th>
                                        <th class="table-th text-white text-center">ORIGEN</th>
                                        <th class="table-th text-white text-center">DESTINO</th>
                                        <th class="table-th text-white text-center">USUARIO</th>
                                        <th class="table-th text-white text-center">FECHA</th>
                                        <th class="table-th text-white text-center">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if($reportRange == 1 && ($dateFrom == '' || $dateTo == ''))
                                    <tr>
                                        <td colspan="10">
                                            <h6 class="text-center text-muted">Sin resultados</h6>
                                        </td>
                                    </tr>
                                    @else

                                    @foreach ($transfer as $t)

                                    <tr>
                                        <td class="text-center">
                                            <h6>{{ $t->product->code }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ $t->product->brand }} | {{$t->product->threshing}} |
                                                {{$t->product->tarp}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>${{ $t->product->cost }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ $t->pf }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ $t->quantity }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>${{ number_format($t->total,2) }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ $t->from_office }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ $t->to_office }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ $t->user }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{Carbon\Carbon::parse($t->created_at)->format('d-m-Y')}}</h6>
                                        </td>
                                        @if(Carbon\Carbon::parse($t->created_at)->format('d-m-Y') ==
                                        Carbon\Carbon::today()->format('d-m-Y'))
                                        @can('cancelar_traspaso')
                                        <td>
                                            <a href="javascript:void(0)" onclick="Confirm_2('{{$t->id}}')"
                                                class="btn btn-dark" title="Anular">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                        @endcan
                                        @endif
                                    </tr>

                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    @if ($reportType == 3)
                    <div class="col-sm-12 col-md-9">
                        <div class="table-responsive-xxl">
                            <table class="table table-sm table-striped table-bordered mt-1">
                                <thead class="text-white" style="background: #3B3F5C">
                                    <tr>
                                        <th class="table-th text-white text-center">PRODUCTO</th>
                                        <th class="table-th text-white text-center">MARCA</th>
                                        <th class="table-th text-white text-center">COSTO</th>
                                        <th class="table-th text-white text-center">PRECIO</th>
                                        <th class="table-th text-white text-center">P/VENTA</th>
                                        <th class="table-th text-white text-center">CANT</th>
                                        <th class="table-th text-white text-center">TOTAL</th>
                                        <th class="table-th text-white text-center">UTILIDAD</th>
                                        <th class="table-th text-white text-center">SUCURSAL</th>
                                        <th class="table-th text-white text-center">N° RECIBO</th>
                                        <th class="table-th text-white text-center">USUARIO</th>
                                        <th class="table-th text-white text-center">FECHA</th>
                                        <th class="table-th text-white text-center">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if($reportRange == 1 && ($dateFrom == '' || $dateTo == ''))
                                    <tr>
                                        <td colspan="10">
                                            <h6 class="text-center text-muted">Sin resultados</h6>
                                        </td>
                                    </tr>
                                    @else

                                    @foreach ($sale as $s)

                                    <tr>
                                        <td class="text-center">
                                            <h6>{{ $s->product->code }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ $s->product->brand }} | {{$s->product->threshing}} |
                                                {{$s->product->tarp}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>${{ $s->product->cost }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>${{ $s->product->price }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>${{ $s->total / $s->quantity}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ $s->quantity }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>${{ number_format($s->total,2) }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>${{ $s->utility }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ $s->office }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ $s->pf }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{ $s->user }}</h6>
                                        </td>
                                        <td class="text-center">
                                            <h6>{{Carbon\Carbon::parse($s->created_at)->format('d-m-Y')}}</h6>
                                        </td>
                                        @if(Carbon\Carbon::parse($s->created_at)->format('d-m-Y') ==
                                        Carbon\Carbon::today()->format('d-m-Y'))
                                        @can('cancelar_venta')
                                        <td>
                                            <a href="javascript:void(0)" onclick="Confirm_3('{{$s->id}}')"
                                                class="btn btn-dark" title="Anular">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                        @endcan
                                        @endif
                                    </tr>

                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif--}}
                </div>
            </div>
        </div>
    </div>
    {{--@include('livewire.report.details')--}}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        flatpickr(document.getElementsByClassName('flatpickr'), {
            enableTime: false,
            dateFormat: 'Y-m-d',
            locale: {
                firstDayofWeek: 1,
                weekdays: {
                    shorthand: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                    longhand: [
                    "Domingo",
                    "Lunes",
                    "Martes",
                    "Miércoles",
                    "Jueves",
                    "Viernes",
                    "Sábado",
                    ],
                },
                months: {
                    shorthand: [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Ago",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dic",
                    ],
                    longhand: [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre",
                    ],
                },
            }
        })

        
        window.livewire.on('show-modal', Msg => {   //evento para mostrar modal
            $('#modalDetails').modal('show')
        })

        window.livewire.on('report-error', msg => {   //evento para los errores del componente
            noty(msg,2)
        })

        window.livewire.on('movement-deleted', msg=>{   //evento para eliminar registro
            noty(msg,2)
        });

    })

    function Confirm_1(id){

        swal({

            title: 'CONFIRMAR',
            text: '¿CONFIRMA ANULAR EL INGRESO?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'CERRAR',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'ACEPTAR'

        }).then(function(result){

            if(result.value){

                window.livewire.emit('remove_income', id)
                swal.close()
            }
        })
    }

    function Confirm_2(id){

        swal({

            title: 'CONFIRMAR',
            text: '¿CONFIRMA ANULAR EL TRASPASO?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'CERRAR',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'ACEPTAR'

        }).then(function(result){

            if(result.value){

                window.livewire.emit('remove_transfer', id)
                swal.close()
            }
        })
    }

    function Confirm_3(id){

        swal({

            title: 'CONFIRMAR',
            text: '¿CONFIRMA ANULAR LA VENTA?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'CERRAR',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'ACEPTAR'

        }).then(function(result){

            if(result.value){

                window.livewire.emit('remove_sale', id)
                swal.close()
            }
        })
    }

</script>