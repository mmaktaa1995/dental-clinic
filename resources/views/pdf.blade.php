<?php
/**
 * @var \App\Models\Patient $patient
 * @var \App\Models\Payment[] $payments
 */

?>

    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Aktaa Dental | {{$patient->name}}</title>
    {{--    <link rel="stylesheet" href="{{asset('print.css')}}">--}}
    <style>
        /* arabic */
        @font-face {
            font-family: 'DejaVu Sans';
            ascent-override: 90%;
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url(../../public/fonts/DejaVuSansCondensed.ttf);
        }

        /* arabic */
        @font-face {
            font-family: 'DejaVu Sans';
            ascent-override: 90%;
            font-style: normal;
            font-weight: 500;
            font-display: swap;
            src: url(../../public/fonts/DejaVuSansMono-Bold.ttf);
        }
        /* arabic */
        @font-face {
            font-family: 'Tajawal';
            ascent-override: 90%;
            font-style: normal;
            font-weight: 200;
            font-display: swap;
            src: url(../../public/fonts/Tajawal-Light.ttf);
        }

        /* arabic */
        @font-face {
            font-family: 'Tajawal';
            ascent-override: 90%;
            font-style: normal;
            font-weight: 300;
            font-display: swap;
            src: url(../../public/fonts/Tajawal-Regular.ttf);
        }

        /* arabic */
        @font-face {
            font-family: 'Tajawal';
            ascent-override: 90%;
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url(../../public/fonts/Tajawal-Medium.ttf);
        }

        /* arabic */
        @font-face {
            font-family: 'Tajawal';
            ascent-override: 90%;
            font-style: normal;
            font-weight: 500;
            font-display: swap;
            src: url(../../public/fonts/Tajawal-Bold.ttf);
        }

        /* arabic */
        @font-face {
            font-family: 'Tajawal';
            ascent-override: 90%;
            font-style: normal;
            font-weight: 700;
            font-display: swap;
            src: url(../../public/fonts/Tajawal-ExtraBold.ttf);
        }

        body { font-family: Tajawal; }

        .text-center {
            text-align: center;
        }

        .heading_table {
            width: 100%;
            color: #ffffff;
            border-collapse: collapse;
        }

        .heading_table td {
            font-size: 16px;
            font-weight: bold;
            padding: 10px 10px 10px 20px;
        }

        .heading_text {
            border-bottom: 2px solid #bbb;
            margin: 20px 0 10px 0;
            font-size: 16px;
        }

        .data_table {
            margin-top: 5px;
            width: 100%;
            border-collapse: collapse;
            color: #444444;
        }

        .data_table th {
            font-size: 16px;
            color: #ffffff;
            background-color: #2eacdd;
            padding: 10px;
            text-align: left;
            border: 2px solid #fff;


        }

        /* .data_table tr:nth-child(even) {background-color: #f2f2f2;}*/
        .data_table td {
            font-size: 14px;
            padding: 8px;
            word-break: normal;
            border-bottom: 1px solid #ededed;
        }

        .data_table tbody td:first-child {
            font-size: 13px;
        }

        .data_table tbody td:not(:first-child) {
            white-space: nowrap;
        }

        .highlight {
            background-color: #fcf8e3;
        }

        .total td {
            background-color: #f1f1f1;
            font-weight: bold;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .f-w-bold {
            font-weight: bold;
        }

        .final {
            background-color: #b6e2ef;
            font-weight: bold;
        }

        .c-brand-lightblue {
            color: #008cbf;
        }

        .c-brand {
            color: #00416a;
        }

        .c-success {
            color: #2bba68;
        }

        .c-warning {
            color: #f0ad4e;
        }

        .c-danger {
            color: #db544e;
        }

        .symbol {
            font-size: 8px;
        }

        .small_font {
            font-size: 85%;
        }

        .c-bg-greylight {
            background: #F9F9F9;
        }
    </style>
</head>
<body class="antialiased" dir="rtl" style="direction:rtl;text-align: right">
<div class="">
    <div>
        <div style="border-bottom: 2px solid #bbb; padding-bottom: 15px;  margin-right:0px; margin-left:0px;">
            <table class="heading_table">
                <tr>
                    <td align="right" style=" background-color: #00416a; border-radius: 0 0 50px 0; text-transform: uppercase;">
                        رقم ملف المريض: {{$patient->file_number}}
                    </td>
                    <td align="left" style="background-color: #ffffff; padding:0px 10px 0px 10px;" width="18%">
                        <img src="{{asset('images/logo.png')}}" width="100px" alt="">
                    </td>
                </tr>
            </table>
            <table class="data_table">
                <thead>
                <tr>
                    <th colspan="2" class="dash__table-head" style="text-align: right">
                        معلومات المريض
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="text-align: right">الاسم</td>
                    <td style="text-align: right">{{trim($patient->name)}}</td>
                </tr>
                <tr>
                    <td style="text-align: right">العمر</td>
                    <td style="text-align: right">{{$patient->age??'-'}}</td>
                </tr>
                <tr>
                    <td style="text-align: right">رقم الموبايل</td>
                    <td style="text-align: right">{{$patient->mobile??$patient->phone}}</td>
                </tr>
                </tbody>
            </table>

            <table class="data_table">
                <thead>
                <tr>
                    <th colspan="3" class="dash__table-head" style="text-align: right">
                        الدفعات
                    </th>
                </tr>
                <tr>
                    <th class="dash__table-head" style="text-align: right">الإجراء الذي تم</th>
                    <th class="dash__table-head" style="text-align: right">المبلغ</th>
                    <th class="dash__table-head" style="text-align: right">التاريخ</th>
                </tr>
                </thead>
                <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <td style="text-align: right">{{$payment->visit->notes??'-'}}</td>
                        <td style="text-align: right">{{number_format($payment->amount)}}</td>
                        <td style="text-align: right">{{$payment->date}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="c-brand f-w-bold" style="font-weight: bold; text-align: right">إجمالي المبلغ
                        الدفوع</td>
                    <td colspan="2" style="text-align: right">{{number_format($totalPayments)}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
