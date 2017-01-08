@extends('layout.app')

@section('content')
    <!-- Hero Content -->
    <div class="bg-primary-dark">
        <section class="content content-full content-boxed">
            <!-- Section Content -->
            <div class="push-100-t push-50 text-center">
                <h1 class="h2 text-white push-10">
                    Queued Job
                </h1>
                <h2 class="h5 text-white-op">
                    Your analysis has been queued. You can monitor the status in this page.
                </h2>
            </div>
            <!-- END Section Content -->
        </section>
    </div>
    <!-- END Hero Content -->
    <!-- Categories -->
    <div class="bg-white">
        <section class="content content-boxed overflow-hidden">
            <!-- Section Content -->
            <div class="content-grid">
                <div class="row">
                    <div class="col-xs-6 col-sm-3 col-sm-offset-3">
                        <a class="block block-bordered block-rounded block-link-hover3" href="javascript:void(0)">
                            <div class="block-content block-content-full border-b text-center">
                                <div class="item">
                                    <i class="fa fa-gears text-amethyst"></i>
                                </div>
                            </div>
                            <div class="block-content block-content-full block-content-mini">
                                <span class="font-w600 text-uppercase">
                                    <span class="badge badge-default pull-right">{{$ahead}}</span> jobs ahead of yours</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-xs-6 col-sm-3">
                        <a class="block block-bordered block-rounded block-link-hover3" href="javascript:void(0)">
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
                </div>
            </div>
            <!-- END Section Content -->
        </section>
    </div>
    <!-- END Categories -->
    <div>
        <section class="content content-boxed overflow-hidden" id="disease-container">
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
                                <h3 class="block-title">Analysis Description</h3>
                            </div>
                            <div class="block-content">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-6">
                                        <dl class="dl-horizontal">
                                            <dt>Id</dt>
                                            <dd>
                                                <a class="link-effect" href="Javascript: void(0);" data-toggle="tooltip"
                                                   data-placement="top"
                                                   title="Save this identifier if you want to review the results at a later time.">
                                                    {{ $jobData->job_key }}
                                                </a>
                                            </dd>
                                            <dt>Disease</dt>
                                            <dd>{{ $disease }}</dd>
                                            <dt>Extraction p-value</dt>
                                            <dd>{{ $jobData->getParameter('extractionMaxPValue', 0.05) }}</dd>
                                        </dl>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6">
                                        <dl class="dl-horizontal">
                                            <dt>Enrichment p-value</dt>
                                            <dd>{{ $jobData->getParameter('annotationMaxPValue', 0.05) }}</dd>
                                            <dt>Min number of nodes</dt>
                                            <dd>{{ $jobData->getParameter('minNumberOfNodes', 5) }}</dd>
                                            <dt>Backward visit</dt>
                                            <dd>
                                                @if ($jobData->getParameter('backward', false))
                                                    <i class="fa fa-check"></i>
                                                @else
                                                    <i class="fa fa-times"></i>
                                                @endif
                                            </dd>
                                        </dl>
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
    <section class="content content-boxed overflow-hidden">
        <div class="row">
            <div class="col-xs-12">
                <p class="h5 font-w600 push-30-t push">
                    This page will automatically refresh in <span class="refresh-counter">30</span> seconds.
                </p>
            </div>
        </div>
    </section>

@endsection
@push('inline-scripts')
<script>
    $(function () {
        var counter = 30;
        var interval = setInterval(function () {
            counter--;
            $('.refresh-counter').text(counter);
            if (counter == 0) {
                clearInterval(interval);
                location.reload();
            }
        }, 1000);
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush