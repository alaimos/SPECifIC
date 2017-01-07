@extends('layout.app')

@section('content')
    <!-- Hero Content -->
    <div class="bg-primary-dark">
        <section class="content content-full content-boxed">
            <!-- Section Content -->
            <div class="push-50-t push-50 text-center">
                <h1 class="h2 text-white push-10">
                    Pathway Substructures
                </h1>
                <h2 class="h5 text-white-op">
                    From this page you can view all pathway substructures found by the analysis.
                </h2>
            </div>
            <!-- END Section Content -->
        </section>
    </div>
    <!-- END Hero Content -->
    <div class="bg-white">
        <section class="content content-boxed overflow-hidden">
            <!-- Section Content -->
            <div class="content-grid">
                <div class="row">
                    <div class="col-xs-6 col-sm-3 col-sm-offset-3">
                        <a class="block block-bordered block-rounded block-link-hover3" href="#nois-container">
                            <div class="block-content block-content-full border-b text-center">
                                <div class="item">
                                    <i class="fa fa-cubes text-city"></i>
                                </div>
                            </div>
                            <div class="block-content block-content-full block-content-mini">
                                <span class="font-w600 text-uppercase"><span
                                            class="badge badge-default pull-right">{{ $nois->count() }}</span> Nodes of Interest</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-xs-6 col-sm-3">
                        <a class="block block-bordered block-rounded block-link-hover3" href="#structures-container">
                            <div class="block-content block-content-full border-b text-center">
                                <div class="item">
                                    <i class="fa fa-codepen text-city"></i>
                                </div>
                            </div>
                            <div class="block-content block-content-full block-content-mini">
                                <span class="font-w600 text-uppercase"><span
                                            class="badge badge-default pull-right">{{ $numStructures }}</span> Sub-Structures</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!-- END Section Content -->
        </section>
    </div>
    <div>
        <section class="content content-boxed overflow-hidden" id="nois-container">
            <!-- Section Content -->
            <div class="content-grid">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="block block-themed block-rounded">
                            <div class="block-header bg-primary-dark">
                                <ul class="block-options">
                                    <li>
                                        <button type="button" data-toggle="block-option"
                                                data-action="fullscreen_toggle"></button>
                                    </li>
                                    <li>
                                        <button type="button" data-toggle="block-option"
                                                data-action="content_toggle"></button>
                                    </li>
                                </ul>
                                <h3 class="block-title">Nodes of Interest</h3>
                            </div>
                            <div class="block-content">
                                <table class="table table-bordered table-striped js-dataTable-full">
                                    <thead>
                                    <tr>
                                        <th style="width: 15%">Id</th>
                                        <th class="hidden-xs">Name</th>
                                        <th style="width: 10%">Details</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($nois as $n)
                                        <tr>
                                            <td>
                                                <a href="Javascript:;" data-id="{{$n->accession}}"
                                                   class="link-effect search-accession">
                                                    {{$n->accession}}
                                                </a>
                                            </td>
                                            <td>{{$n->name}}</td>
                                            <td class="text-center">
                                                <a href="{{$n->getUrl()}}" class="btn btn-info text-center"
                                                   target="_blank"><i class="fa fa-external-link">&nbsp;</i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Section Content -->
        </section>
    </div>
    <div>
        <section class="content content-boxed overflow-hidden" id="structures-container">
            <!-- Section Content -->
            <div class="content-grid">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="block block-themed block-rounded block-bordered">
                            <div class="block-header bg-primary-dark">
                                <ul class="block-options">
                                    <li>
                                        <button type="button" data-toggle="block-option"
                                                data-action="fullscreen_toggle"></button>
                                    </li>
                                    <li>
                                        <button type="button" data-toggle="block-option"
                                                data-action="content_toggle"></button>
                                    </li>
                                </ul>
                                <h3 class="block-title">Sub-Structures</h3>
                            </div>
                            <div class="block-content">
                                <table class="table table-bordered table-striped js-dataTable-structures" style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th>Start Node</th>
                                        <th>Type</th>
                                        <th># Nodes</th>
                                        <th>Perturbation</th>
                                        <th>p-Value</th>
                                        <th>Enrichment</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Start Node</th>
                                        <th data-values="Community,Induced-Subgraph,Neighborhood,Path,Tree">Type</th>
                                        <th># Nodes</th>
                                        <th>Perturbation</th>
                                        <th>p-Value</th>
                                        <th data-disabled="1">Enrichment</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Section Content -->
        </section>
    </div>
@endsection
@push('inline-scripts')
<script>
    $(function () {
        $('.js-dataTable-full').dataTable({
            columnDefs: [],
            pageLength: 10,
            lengthMenu: [[5, 10, 15, 20], [5, 10, 15, 20]]
        });
        $('.js-dataTable-structures').dataTable({
            processing:   true,
            serverSide:   true,
            ajax:         {
                url:    '{{ route('extraction-structures', ['jobKey' => $jobData->job_key]) }}',
                method: 'POST'
            },
            columns:      [
                {data: 'root', name: 'root'},
                {data: 'type', name: 'type'},
                {data: 'nodes', name: 'nodes'},
                {data: 'accumulator', name: 'accumulator'},
                {data: 'pvalue', name: 'pvalue'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false},
            ],
            columnDefs:   [
                {responsivePriority: 1, targets: 0},
                {responsivePriority: 1, targets: 1},
                {className: 'text-right', targets: 2},
                {className: 'text-right', targets: 3},
                {className: 'text-right', targets: 4},
                {className: 'text-center', targets: 5}
            ],
            pageLength:   10,
            lengthMenu:   [[5, 10, 15, 20], [5, 10, 15, 20]],
            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this, $column = $(column.footer()), input, values = $column.data('values');
                    if (parseInt($column.data('disabled')) == 1) {
                        return;
                    }
                    if (values != undefined) {
                        var chldOption = function (v, s) {
                            var chld = document.createElement("option"), $chld = $(chld);
                            if (v) $chld.attr('value', v).text(v);
                            if (s) $chld.attr('selected', 'selected');
                            return chld;
                        };
                        input = document.createElement("select");
                        input.appendChild(chldOption(null, true));
                        $.each(values.split(","), function (i, v) {
                            input.appendChild(chldOption(v));
                        });
                    } else {
                        input = document.createElement("input");
                        $(input).attr('placeholder', $column.text());
                    }
                    $(input).appendTo($column.empty()).on('change', function () {
                        column.search($(this).val(), false, false, true).draw();
                    });
                });
            }
        });
        $('.search-accession').click(function () {
            var id = $(this).data('id');
            $('.js-dataTable-structures').find('tfoot').find('th > input').first().val(id).change();
        });
    });
</script>
@endpush