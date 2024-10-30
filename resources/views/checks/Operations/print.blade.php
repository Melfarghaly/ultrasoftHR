@extends('layouts.app')

@section('content')
    <style>
        .print-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            font-family: 'Arial', sans-serif;
            border: 2px solid #000;
            background: #fff;
            position: relative;
        }
        .bank-logo {
            position: absolute;
            top: 20px;
            left: 20px;
        }
        .check-header {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: bold;
        }
        .check-details {
            margin-top: 80px;
        }
        .check-details div {
            margin-bottom: 15px;
            font-size: 16px;
        }
        .check-details label {
            font-weight: bold;
            margin-right: 10px;
        }
        .check-footer {
            text-align: center;
            margin-top: 30px;
        }
        .signature {
            border-top: 1px solid #000;
            width: 300px;
            margin: 0 auto;
            margin-top: 50px;
        }
        .btn-custom {
            margin-right: 10px;
        }
    </style>

    <div class="container mt-5">
        <div class="text-center mb-4">
            <h1>تفاصيل الشيك</h1>
        </div>


        <div class="print-container">
            <div class="check-header">
                <h1>شيك</h1>
            </div>
            
            <div class="check-details">
                <div><label>رقم الشيك:</label> {{ $check->check_number }}</div>
                <div><label>اسم البنك:</label> {{ $check->bank }}</div>
                <div><label>اسم الحساب:</label> {{ $check->account_name }}</div>
                <div><label>تاريخ التحرير:</label> {{ $check->issue_date->format('Y-m-d') }}</div>
                <div><label>تاريخ الاستحقاق:</label> {{ $check->due_date->format('Y-m-d') }}</div>
                <div><label>قيمة الشيك:</label> {{ $check->amount }} {{ $check->currency }}</div>
                <div><label>إلى:</label> {{ $check->pay_to }}</div>
                <div><label>ملاحظات:</label> {{ $check->notes }}</div>
            </div>
            
            <div class="check-footer">
                <div class="signature">
                    التوقيع
                </div>
            </div>
        </div>
    </div>

    <script>
        function printCheck() {
            window.print();
        }
    </script>
@endsection
