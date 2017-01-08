@extends('layout.app')

@section('content')
    <!-- Hero Content -->
    <div class="bg-primary-dark">
        <!-- Search Content -->
        <section class="content content-full content-boxed overflow-hidden">
            <div class="push-50-t push-50">
                <h1 class="font-s48 font-w700 text-white push-10 visibility-hidden text-center" data-toggle="appear"
                    data-class="animated fadeInDown">
                    History of previous analysis
                </h1>
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3">
                        <div class="block">
                            <form class="submission-form form-horizontal" action="{{ route('submit-history') }}"
                                  method="post">
                                {!! csrf_field() !!}
                                <div class="block-content mheight-200">
                                    <div class="push-50-t push-50">
                                        <div class="form-group{{ $errors->has('jobIdentifier') ? ' has-error' : '' }}">
                                            <div class="col-sm-8 col-sm-offset-2">
                                                <label for="history-form-job-identifier">Specify a job
                                                    identifier</label>
                                                <input type="text" name="jobIdentifier" id="history-form-job-identifier"
                                                       value="{{ old('jobIdentifier') }}" class="form-control">
                                                @if ($errors->has('jobIdentifier'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('jobIdentifier') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="block-content block-content-mini block-content-full border-t">
                                    <div class="row">
                                        <div class="col-xs-12 text-right">
                                            <button class="btn btn-success" type="submit"><i class="fa fa-search"></i>
                                                Search
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END Search Content -->
    </div>
    <!-- END Hero Content -->

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
                                <h3 class="block-title">Latest 100 jobs</h3>
                            </div>
                            <div class="block-content">
                                <table class="table table-bordered table-striped js-dataTable-full">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Disease</th>
                                        <th>Node of interests</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($jobs as $j)
                                        <tr>
                                            <td>
                                                <a href="{{ route('extraction-results', ['jobKey' => $j->job_key]) }}"
                                                   class="link-effect">
                                                    {{ $j->job_key }}
                                                </a>
                                            </td>
                                            <td>{{ $getDisease($j) }}</td>
                                            <td>
                                                {!! $getNois($j) !!}
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

@endsection
@push('inline-scripts')
<script>
    $(function () {
        $('.js-dataTable-full').dataTable({
            columnDefs: [],
            pageLength: 10,
            lengthMenu: [[5, 10, 15, 20], [5, 10, 15, 20]]
        });
    });
</script>
@endpush