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