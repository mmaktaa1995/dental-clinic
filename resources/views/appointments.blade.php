@extends('layouts.app')
@section('title',trans('main.appointments'))
@section('content')
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12">

                    <!--begin::Portlet-->
                    <div class="kt-portlet">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <span class="kt-portlet__head-icon">
                                    <i class="flaticon-list"></i>
                                </span>
                                <h3 class="kt-portlet__head-title">
                                    {{__("main.appointments")}}
                                </h3>
                            </div>
                            <div class="kt-portlet__head-toolbar">
                                <div class="kt-portlet__head-wrapper">
                                    <div class="dropdown dropdown-inline">
                                        <a href="{{route('add-appointment')}}" target="_blank" class="btn btn-brand btn-icon-sm"
                                                aria-haspopup="true" aria-expanded="false">
                                            <i class="flaticon2-plus"></i> {{__('main.add-appointment')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div id="kt_calendar"></div>
                        </div>
                    </div>

                    <!--end::Portlet-->
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="kt-font-primary">{{__('app.date')}}:</h5>
                    <span id="eventDate"></span>
                    <br>
                    <br>
                    <h5 class="kt-font-primary">{{__('main.notes')}}:</h5>
                    <p id="eventNotes"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('app.close')}}</button>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <script src="{{asset('assets/vendors/custom/jquery-ui/jquery-ui.bundle.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/custom/fullcalendar/fullcalendar.bundle.js')}}" type="text/javascript"></script>
    <script>
        var _events = [];

        @foreach($appointments as $item)
        _events.push(
            {
                id: '{{$item->id}}',
                'title': '{{$item->patient->full_name}}',
                "start": '{{str_replace(' ','T',$item->date)}}',
                "end": '{{str_replace(' ','T',\Carbon\Carbon::parse($item->date)->addMinutes(30))}}',
                "date": '{{$item->date}}',
                'description': '{{$item->notes}}',
                'className': '{{\Carbon\Carbon::parse($item->date)<now()?' bg-red-500':'bg-teal-500'}}'
            }
        );
        @endforeach
    </script>
{{--    <script src="https://unpkg.com/@fullcalendar/core@4.3.1/locales-all.js" type="text/javascript"></script>--}}
    <script src="{{asset('assets/js/demo1/pages/components/calendar/external-events.js')}}" type="text/javascript"></script>
@endpush
@push('styles')
    <link href="{{asset('assets/vendors/custom/jquery-ui/jquery-ui.bundle'.$rtl.'.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/vendors/custom/fullcalendar/fullcalendar.bundle'.$rtl.'.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        td.fc-event-container{
            cursor: pointer;
        }
    </style>
@endpush
