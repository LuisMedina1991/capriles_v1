<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title text-uppercase">
                    <b>{{$pageTitle}} | {{$componentName}}</b>
                </h4>
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-3">
                    <h6><b>Filtro de busqueda</b></h6>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text input-gp">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                            <input type="text" wire:model="search" placeholder="BUSCAR..." class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                <th class="table-th text-white text-center">nombre de estado</th>
                                <th class="table-th text-white text-center">tipo de estado</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($statuses as $status)

                            <tr>
                                <td>
                                    <h6 class="text-center text-uppercase">{{ $status->name }}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{ $status->type }}</h6>
                                </td>
                            </tr>

                            @endforeach

                        </tbody>
                    </table>

                    {{$statuses->links()}}
                    
                </div>
            </div>
        </div>
    </div>
</div>