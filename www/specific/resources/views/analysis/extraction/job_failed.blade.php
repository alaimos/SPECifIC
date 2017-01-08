@extends('layout.app')

@section('content')
    <!-- Hero Content -->
    <div class="bg-danger">
        <section class="content content-full content-boxed">
            <!-- Section Content -->
            <div class="push-100-t push-50 text-center">
                <h1 class="h2 text-white push-10">
                    Job Failed
                </h1>
                <h2 class="h5 text-white-op">
                    Your analysis failed during processing.
                </h2>
            </div>
            <!-- END Section Content -->
        </section>
    </div>
    <!-- END Hero Content -->
    <div>
        <section class="content content-boxed overflow-hidden" id="disease-container">
            <!-- Section Content -->
            <div class="content-grid">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="block block-themed block-rounded">
                            <div class="block-header bg-danger">
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
                <div class="block block-themed block-rounded">
                    <div class="block-header bg-danger">
                        <ul class="block-options">
                            <li>
                                <button type="button" data-toggle="block-option"
                                        data-action="content_toggle"></button>
                            </li>
                        </ul>
                        <h3 class="block-title">Activity Log</h3>
                    </div>
                    <div class="block-content">
                        <pre class="pre-sh">{{ $jobData->job_log }}</pre>
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Error Footer -->
    <section class="content text-muted text-center">
        Do you think there is a bug? Please let us know about it by
        <a class="link-effect" href="https://github.com/alaimos/SPECifIC/issues">reporting it</a>.
    </section>
@endsection
@push('inline-scripts')
<script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush