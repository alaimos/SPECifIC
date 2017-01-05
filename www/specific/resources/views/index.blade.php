@extends('layout.app')

@section('content')
    <!-- Hero Content -->
    <div class="bg-primary-dark">
        <!-- Search Content -->
        <section class="content content-full content-boxed overflow-hidden">
            <div class="push-50-t push-50">
                <h1 class="font-s48 font-w700 text-white push-10 visibility-hidden text-center" data-toggle="appear"
                    data-class="animated fadeInDown">
                    <span class="text-primary">S</span><span class="">P</span><span class="text-primary">E</span><span
                            class="">C</span><span class="text-primary">i</span><span class="">f</span><span
                            class="text-primary">I</span><span class="">C</span>
                </h1>
                <h2 class="h3 font-w400 text-white-op push-50 visibility-hidden text-center" data-toggle="appear"
                    data-timeout="750">
                    Sub-Pathway Extractor and Enricher
                </h2>
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3">
                        <div class="js-wizard-simple block">
                            <!-- Step Tabs -->
                            <ul class="nav nav-tabs nav-justified">
                                <li class="active">
                                    <a href="#submission-form-step1" data-toggle="tab">1. Select a disease</a>
                                </li>
                                <li>
                                    <a href="#submission-form-step2" data-toggle="tab">2. Nodes of Interest</a>
                                </li>
                                <li>
                                    <a href="#submission-form-step3" data-toggle="tab">3. Extra</a>
                                </li>
                            </ul>
                            <!-- END Step Tabs -->

                            <!-- Form -->
                            <form class="submission-form form-horizontal" action="{{ route('submit-extraction') }}"
                                  method="post">
                            {!! csrf_field() !!}
                            <!-- Steps Content -->
                                <div class="block-content tab-content" style="min-height: 242px;">
                                    <!-- Step 1 -->
                                    <div class="tab-pane push-50-t push-50 active" id="submission-form-step1">
                                        <div class="form-group">
                                            <div class="col-sm-8 col-sm-offset-2">
                                                <label for="submission-form-select-disease">Select a disease</label>
                                                <select class="js-select2 form-control" required
                                                        id="submission-form-select-disease"
                                                        name="disease" data-placeholder="Select a disease">
                                                    <option></option>
                                                    @foreach($diseases as $key => $val)
                                                        <option value="{{ $key }}">{{ $val }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END Step 1 -->
                                    <!-- Step 2 -->
                                    <div class="tab-pane push-50-t push-50" id="submission-form-step2">
                                        <div class="form-group">
                                            <div class="col-sm-8 col-sm-offset-2">
                                                <label for="submission-form-select-noi">Select one or more nodes of
                                                    interest</label>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <select class="form-control" required
                                                                id="submission-form-select-noi" name="nois[]" multiple>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END Step 2 -->
                                    <!-- Step 3 -->
                                    <div class="tab-pane push-5-t push-10" id="submission-form-step3">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="submission-form-max-pvalue">
                                                Extraction Max p-value
                                            </label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="number"
                                                       id="submission-form-max-pvalue" name="max-pvalue" step="any"
                                                       value="0.05">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"
                                                   for="submission-form-max-pvalue-annot">
                                                Annotation Max p-value
                                            </label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="number"
                                                       id="submission-form-max-pvalue-annot" name="max-pvalue-annot"
                                                       step="any" value="0.05">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="submission-form-min-num-nodes">
                                                Min number of nodes
                                            </label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="number"
                                                       id="submission-form-min-num-nodes"
                                                       name="min-num-nodes" value="5">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-8 col-sm-offset-4">
                                                <label class="css-input switch switch-sm switch-primary"
                                                       for="submission-form-backward-visit">
                                                    <input type="checkbox" id="submission-form-backward-visit"
                                                           name="backward-visit"><span></span> Backward visit
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END Step 3 -->
                                </div>
                                <!-- END Steps Content -->
                                <!-- Steps Navigation -->
                                <div class="block-content block-content-mini block-content-full border-t">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <button class="wizard-prev btn btn-default disabled" type="button"><i
                                                        class="fa fa-arrow-left"></i> Previous
                                            </button>
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <button class="wizard-next btn btn-default" type="button">Next <i
                                                        class="fa fa-arrow-right"></i></button>
                                            <button class="wizard-finish btn btn-primary" type="submit"
                                                    style="display: none;"><i class="fa fa-check"></i> Submit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- END Steps Navigation -->
                            </form>
                            <!-- END Form -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END Search Content -->
    </div>
    <!-- END Hero Content -->

    <div class="bg-white">
        <section class="content content-full content-boxed">
            <div class="row push-50">
                <div class="col-sm-6 col-sm-offset-3 nice-copy-story">
                    <p class="text-justify">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras gravida nunc vitae dolor interdum,
                        vel dapibus nulla posuere. Curabitur rutrum leo et diam mollis tristique. Nam tempor consectetur
                        rhoncus. Praesent nibh mi, rutrum ut posuere ac, commodo vel nulla. Donec blandit leo odio, at
                        sagittis quam malesuada vitae. Donec rutrum feugiat tellus vitae pretium. Integer eget faucibus
                        orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                        Integer bibendum malesuada enim, eget congue dui congue eget. Nam ultricies enim non turpis
                        aliquet blandit. Sed at elit ac eros finibus porta vitae eget lectus. Ut lacinia rhoncus ex.
                        Proin a eros et sem malesuada dapibus.
                    </p>
                    <p class="text-justify">
                        Proin vel nunc dui. Nulla facilisi. Vivamus consequat scelerisque vestibulum. Nam tempor lacus
                        in interdum mollis. Vestibulum vehicula nulla dapibus turpis eleifend imperdiet. Sed at lectus
                        sodales, laoreet purus non, molestie neque. Sed dignissim maximus ipsum eu condimentum.
                        Suspendisse sit amet hendrerit quam. Ut a tristique tellus, id convallis nisi. Cras a nulla id
                        dolor tincidunt dignissim ut in magna. Morbi viverra risus lacus, vitae sagittis tortor luctus
                        at. Aliquam auctor feugiat magna.
                    </p>
                </div>
            </div>
        </section>
    </div>

@endsection
@push('inline-scripts')
<script>
    $(function () {
        var $submissionForm = $('.submission-form'), disease = null;
        $submissionForm.on('keyup keypress', function (e) {
            var code = e.keyCode || e.which;
            if (code === 13) {
                e.preventDefault();
                return false;
            }
        });
        var $validator = $submissionForm.validate({
            errorClass:     'help-block animated fadeInDown',
            errorElement:   'div',
            ignore:         '.ignore',
            errorPlacement: function (error, e) {
                $(e).parents('.form-group > div').append(error);
            },
            highlight:      function (e) {
                $(e).closest('.form-group').removeClass('has-error').addClass('has-error');
                $(e).closest('.help-block').remove();
            },
            success:        function (e) {
                $(e).closest('.form-group').removeClass('has-error');
                $(e).closest('.help-block').remove();
            }
        });


        var loadNodes = function () {
            var $selectNoi = $('#submission-form-select-noi');
            if ($selectNoi.hasClass('ok')) {
                $selectNoi.select2('destroy').removeClass('ok');
            }
            $selectNoi.select2({
                ajax:               {
                    url:            "{{ route('list-nois') }}",
                    dataType:       'json',
                    delay:          250,
                    data:           function (params) {
                        return {
                            disease: disease,
                            q:       params.term, // search term
                            page:    params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        var results = [];
                        $.each(data.data, function (i, v) {
                            results.push({
                                id:        v.accession,
                                text:      v.accession,
                                name:      v.name,
                                accession: v.accession
                            });
                        });
                        return {
                            results:    results,
                            pagination: {
                                more: (params.page * 30) < data.total
                            }
                        };
                    },
                    cache:          true
                },
                minimumInputLength: 1,
                templateResult:     function (result) {
                    if (result.loading) return result.text;
                    return (result.accession + ' - ' + result.name);
                },
                templateSelection:  function (selection) {
                    return selection.accession || selection.text;
                }
            }).addClass('ok');
        };

        var wizardNavigation = function ($tab, $navigation, $index) {
            var valid = $submissionForm.valid(),
                $container = $($('a[data-toggle="tab"]', $tab).attr('href')),
                inCurrentTab = $.map($validator.invalidElements(), function ($e) {
                    return $.contains($container[0], $e);
                }).reduce(function (a, b) {
                    return (a || b);
                }, false);
            if (!valid && inCurrentTab) {
                $validator.focusInvalid();
                return false;
            }
        };


        $('.js-wizard-simple').bootstrapWizard({
            'tabClass':         '',
            'firstSelector':    '.wizard-first',
            'previousSelector': '.wizard-prev',
            'nextSelector':     '.wizard-next',
            'lastSelector':     '.wizard-last',
            'onTabShow':        function ($tab, $navigation, $index) {
                var $total = $navigation.find('li').length;
                var $current = $index + 1;
                var $percent = ($current / $total) * 100;
                // Get vital wizard elements
                var $wizard = $navigation.parents('.block');
                var $progress = $wizard.find('.wizard-progress > .progress-bar');
                //var $btnPrev = $wizard.find('.wizard-prev');
                var $btnNext = $wizard.find('.wizard-next');
                var $btnFinish = $wizard.find('.wizard-finish');
                // Update progress bar if there is one
                if ($progress) {
                    $progress.css({width: $percent + '%'});
                }
                if ($current == 2) {
                    loadNodes();
                }
                // If it's the last tab then hide the last button and show the finish instead
                if ($current >= $total) {
                    $btnNext.hide();
                    $btnFinish.show();
                } else {
                    $btnNext.show();
                    $btnFinish.hide();
                }
            },
            'onNext':           wizardNavigation,
            'onTabClick':       wizardNavigation
        });
        $('#submission-form-select-disease').change(function () {
            disease = $(this).val();
        });
    });
</script>
@endpush