@extends('layout.app')

@section('content')
    <!-- Hero Content -->
    <div class="bg-primary-dark">
        <section class="content content-full content-boxed">
            <!-- Section Content -->
            <div class="push-100-t push-50 text-center">
                <h1 class="h2 text-white push-10">
                    Processing Job
                </h1>
                <h2 class="h5 text-white-op">
                    Your analysis is processing. You can monitor the status in this page.
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
                    <div class="block-header bg-primary-dark">
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
    <section class="content content-boxed overflow-hidden">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
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
    });
</script>
@endpush