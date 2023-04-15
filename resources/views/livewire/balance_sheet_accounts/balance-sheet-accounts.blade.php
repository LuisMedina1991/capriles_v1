<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title text-uppercase">
                    <b>{{$pageTitle}} | {{$componentName}}</b>
                </h4>
            </div>

            <div class="row mb-4">
                <div class="col-sm-2">
                    <h6><b>Tipo de cuenta</b></h6>
                    <select id="search" wire:model="search" class="form-control text-uppercase">
                        <option value="elegir">seleccione</option>
                        @foreach ($types as $type)
                        <option value="{{$type->id}}">{{$type->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <h6><b>Estado de cuenta</b></h6>
                    <select id="search_2" wire:model="search_2" class="form-control text-uppercase">
                        <option value="0">activa</option>
                        <option value="1">bloqueada</option>
                    </select>
                </div>
            </div>

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                <th class="table-th text-white text-center">nombre</th>
                                <th class="table-th text-white text-center">alias</th>
                                <th class="table-th text-white text-center">tipo</th>
                                <th class="table-th text-white text-center">subtipo</th>
                                <th class="table-th text-white text-center">saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accounts as $account)
                            <tr>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$account->name}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$account->alias}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$account->subtype->type->name}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$account->subtype->name}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">${{number_format($account->balance,2)}}</h6>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{$accounts->links()}}

                </div>
            </div>
        </div>
    </div>
</div>