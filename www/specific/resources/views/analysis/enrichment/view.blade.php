@extends('layout.app')

@section('content')
    <!-- Hero Content -->
    <div class="bg-primary-dark">
        <section class="content content-full content-boxed">
            <!-- Section Content -->
            <div class="push-50-t push-50 text-center">
                <h1 class="h2 text-white push-10">
                    Substructure Enrichment
                </h1>
            </div>
            <!-- END Section Content -->
        </section>
    </div>
    <!-- END Hero Content -->
    <div>
        <section class="content content-boxed overflow-hidden">
            <!-- Section Content -->
            <div class="content-grid">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="block block-themed block-rounded block-bordered block-cytograph">
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
                                <h3 class="block-title">Sub-Structure View</h3>
                            </div>
                            <div class="block-content">
                                <div id="cy" style="height: 300px; display: block" class="clearfix">
                                    <div class="loading text-center text-primary-darker" style="height: 100%;">
                                        <i class="fa fa-5x fa-cog fa-spin"></i>
                                        <p class="h1 font-w600">Loading</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Section Content -->
        </section>
    </div>
    <div>
        <section class="content content-boxed overflow-hidden">
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
                                <h3 class="block-title">Enrichment Terms</h3>
                            </div>
                            <div class="block-content">
                                <table class="table table-bordered table-striped js-dataTable-terms"
                                       style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Term</th>
                                        <th># Nodes</th>
                                        <th>p-value</th>
                                        <th>Adjusted p-value</th>
                                        <th>Source</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Id</th>
                                        <th>Term</th>
                                        <th># Nodes</th>
                                        <th>p-value</th>
                                        <th>Adjusted p-value</th>
                                        <th data-values="{{ $sources->implode(',') }}">Source</th>
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
    var $cy = $('#cy'), cytoObject, $cytoBlock = $('.block-cytograph');
    $(function () {
        var fullscreen = false;
        $cytoBlock.on('toggleFullscreen', function () {
            if (!fullscreen) {
                var border = Math.ceil(($cytoBlock.find('.block-content').outerHeight() - $cytoBlock.find('#cy').height())/2);
                $cytoBlock.find('#cy').height($cytoBlock.innerHeight() - $cytoBlock.find('.block-header').outerHeight() - border);
            } else {
                $cytoBlock.find('#cy').height(300);
            }
            cytoObject.resize();
            fullscreen = !fullscreen;
        });
        $.ajax({
            url:        '{{ route('enrichment-view', ['jobKey' => $jobData->job_key]) }}',
            method:     'POST',
            dataType:   'json',
            statusCode: {
                404: function () {
                    $cy.html('<div class="text-center text-danger" style="height: 100%;"><i class="fa fa-5x fa-exclamation-triangle"></i><p class="h1 font-w600">Unable to find results to display</p></div>');
                },
                500: function () {
                    $cy.html('<div class="text-center text-danger" style="height: 100%;"><i class="fa fa-5x fa-exclamation-triangle"></i><p class="h1 font-w600">Unable to find process results</p></div>');
                }
            },
            success:    function (data) {
                $cy.find('.loading').remove();
                cytoObject = cytoscape({
                    container: $cy,
                    elements:  data.elements,
                    style:     [
                        {
                            selector: "node",
                            style:    {
                                'border-color': 'black',
                                'border-style': 'solid',
                                'border-width': 2,
                                'content':      'data(id)',
                                'text-valign':  'center',
                                'text-halign':  'center',
                                'width':        'label',
                                'height':       'label',
                                'padding':      20
                            }
                        },
                        {
                            selector: "node[type='gene']",
                            style:    {
                                'shape':            'roundrectangle',
                                'background-color': '#46c37b'
                            }
                        },
                        {
                            selector: "node[type='mirna']",
                            style:    {
                                'shape':            'ellipse',
                                'background-color': '#f3b760'
                            }
                        },
                        {
                            selector: "node[type='compound']",
                            style:    {
                                'shape':            'diamond',
                                'background-color': 'white',
                                'padding':          40
                            }
                        },
                        {
                            selector: "node[type='other']",
                            style:    {
                                'shape':            'heptagon',
                                'background-color': '#777'
                            }
                        },
                        {
                            selector: 'edge',
                            style:    {
                                'curve-style':        'bezier',
                                'target-arrow-shape': 'none',
                                'target-arrow-color': '#999', //#ccc',
                                'line-color':         '#999', //#ccc',
                                'width':              4
                            }
                        },
                        {
                            selector: 'edge[type*="MISSING_INTERACTION"]',
                            style:    {
                                'line-style':         'dotted'
                            }
                        },
                        {
                            selector: 'edge[type*="STATE_CHANGE"]',
                            style:    {
                                'line-style': 'dotted'
                            }
                        },
                        {
                            selector: 'edge[type*="INDIRECT_EFFECT"]',
                            style:    {
                                'line-style':         'dashed',
                                'target-arrow-shape': 'triangle',
                                'target-arrow-fill':  'hollow'
                            }
                        },
                        {
                            selector: 'edge[type*="INHIBITION"],edge[type*="REPRESSION"]',
                            style:    {
                                'target-arrow-shape': 'tee'
                            }
                        },
                        {
                            selector: 'edge[type*="ACTIVATION"],edge[type*="EXPRESSION"]',
                            style:    {
                                'target-arrow-shape': 'triangle'
                            }
                        }
                    ],
                    layout: {
                        name:     'breadthfirst',
                        fit:      false,
                        directed: true
                    },
                    ready:  function (e) {
                        e.cy.panzoom({});
                        e.cy.elements('node').qtip({
                            content: function () {
                                return '<div class="row small">' +
                                    '<div class="col-xs-4 font-w600">Id:</div>' +
                                    '<div class="col-xs-8">' + this.data('id') + '</div>' +
                                    '</div><div class="row small">' +
                                    '<div class="col-xs-4 font-w600">Name:</div>' +
                                    '<div class="col-xs-8">' + this.data('name') + '</div>' +
                                    '</div><div class="row small">' +
                                    '<div class="col-xs-12 text-center"><a href="' + this.data('url') + '" target="_blank" class="link-effect">More details</a></div>' +
                                    '</div>';
                            },
                            style:   {
                                width:   '250px',
                                classes: 'qtip-bootstrap'
                            }
                        });
                        e.cy.elements('edge').qtip({
                            content: function () {
                                return this.data('type');
                            },
                            style:   {
                                width:   '250px',
                                classes: 'qtip-bootstrap'
                            }
                        });
                        e.cy.zoom({
                            level:    1.0,
                            position: e.cy.nodes('[id="' + data.root + '"]').position()
                        });
                    }
                });
            }
        });
        $('.js-dataTable-terms').dataTable({
            processing:   true,
            serverSide:   true,
            ajax:         {
                url:    '{{ route('enrichment-terms', ['jobKey' => $jobData->job_key]) }}',
                method: 'POST'
            },
            columns:      [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'occurrences', name: 'occurrences'},
                {data: 'pvalue', name: 'pvalue'},
                {data: 'adjustedPValue', name: 'adjustedPValue'},
                {data: 'source', name: 'source'},
            ],
            columnDefs:   [
                {className: 'text-right', targets: 2},
                {className: 'text-right', targets: 3},
                {className: 'text-right', targets: 4}
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
    });
</script>
@endpush