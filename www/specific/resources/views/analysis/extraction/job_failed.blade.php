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
    <section class="content content-boxed overflow-hidden">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
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