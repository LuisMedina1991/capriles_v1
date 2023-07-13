<div wire:ignore.self id="product_details_modal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white text-uppercase">
                    <b>detalles de producto</b>
                </h5>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                <th class="table-th text-center text-white">marca</th>
                                <th class="table-th text-center text-white">categoria</th>
                                <th class="table-th text-center text-white">subcategoria</th>
                                <th class="table-th text-center text-white">presentacion</th>
                                <th class="table-th text-center text-white">informacion adicional</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    <h6 class="text-uppercase"><b>{{$brand_name}}</b></h6>
                                </td>
                                <td class="text-center">
                                    <h6 class="text-uppercase"><b>{{$category_name}}</b></h6>
                                </td>
                                <td class="text-center">
                                    <h6 class="text-uppercase"><b>{{$subcategory_name}}</b></h6>
                                </td>
                                <td class="text-center">
                                    <h6 class="text-uppercase"><b>{{$presentation_name}}</b></h6>
                                </td>
                                <td class="text-center">
                                    <h6 class="text-uppercase"><b>{{$additional_info}}</b></h6>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="resetUI()" class="btn btn-dark text-info text-uppercase" data-dismiss="modal">cerrar</button>
            </div>
        </div>
    </div>
</div>