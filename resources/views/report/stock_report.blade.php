@extends('layouts.app')
@if(request()->type=='costprice')
@section('title', __('lang_v1.stock_report_cost_price'))

@elseif(request()->type=='sellprice')
@section('title', __('lang_v1.stock_report_sell_price'))
@else
@section('title', __('report.stock_report'))
@endif
@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    @if(request()->type=='costprice')
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">{{ __('lang_v1.stock_report_cost_price')}}</h1>
    @elseif(request()->type=='sellprice')
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">{{ __('lang_v1.stock_report_sell_price')}}</h1>
    @else
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">{{ __('report.stock_report')}}</h1>
    @endif
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
              {!! Form::open(['url' => action([\App\Http\Controllers\ReportController::class, 'getStockReport']), 'method' => 'get', 'id' => 'stock_report_filter_form' ]) !!}
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('location_id',  __('purchase.business_location') . ':') !!}
                        {!! Form::select('location_id', $business_locations, null, ['class' => 'form-control select2', 'style' => 'width:100%']); !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('category_id', __('category.category') . ':') !!}
                        {!! Form::select('category', $categories, null, ['placeholder' => __('messages.all'), 'class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'category_id']); !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('sub_category_id', __('product.sub_category') . ':') !!}
                        {!! Form::select('sub_category', array(), null, ['placeholder' => __('messages.all'), 'class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'sub_category_id']); !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('brand', __('product.brand') . ':') !!}
                        {!! Form::select('brand', $brands, null, ['placeholder' => __('messages.all'), 'class' => 'form-control select2', 'style' => 'width:100%']); !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('unit',__('product.unit') . ':') !!}
                        {!! Form::select('unit', $units, null, ['placeholder' => __('messages.all'), 'class' => 'form-control select2', 'style' => 'width:100%']); !!}
                    </div>
                </div>
                <div class="col-md-3 hide">
                    <div class="form-group">
                        @php
                            $unites_show=[
                            0=>"الوحدة الرئيسية",
                            1=>"الوحدة الفرعية"
                            ];
                        @endphp
                        {!! Form::label('sub_unit_id', __(' عرض مخزون بـ') . ':') !!}
                        {!! Form::select('sub_unit_id', $unites_show,0, ['class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'product_list_filter_sub_unit_id', 'placeholder' => __('lang_v1.all')]); !!}
                    </div>
                </div>
        
                @if($show_manufacturing_data)
                    <div class="col-md-3">
                        <div class="form-group">
                            <br>
                            <div class="checkbox">
                                <label>
                                  {!! Form::checkbox('only_mfg', 1, false, 
                                  [ 'class' => 'input-icheck', 'id' => 'only_mfg_products']); !!} {{ __('manufacturing::lang.only_mfg_products') }}
                                </label>
                            </div>
                        </div>
                    </div>
                @endif
                {!! Form::close() !!}
            @endcomponent
        </div>
    </div>
    @can('view_product_stock_value')
    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['class' => 'box-solid'])
            <table class="table no-border">
                <tr>
                    @if(request()->type=='costprice')
                    <td>@lang('report.closing_stock') (@lang('lang_v1.by_purchase_price'))</td>
                    @endif
                    @if(request()->type=='sellprice')
                    <td>@lang('report.closing_stock') (@lang('lang_v1.by_sale_price'))</td>
                    @endif
                    <td class="hide">@lang('lang_v1.potential_profit')</td>
                    <td class="hide">@lang('lang_v1.profit_margin')</td>
                </tr>
                <tr>
                    @if(request()->type=='costprice')
                    <td><h3 id="closing_stock_by_pp" class="mb-0 mt-0"></h3></td>
                    @endif
                    @if(request()->type=='sellprice')
                    <td><h3 id="closing_stock_by_sp" class="mb-0 mt-0"></h3></td>
                    @endif
                    <td class="hide"><h3 id="potential_profit" class="mb-0 mt-0"></h3></td>
                    <td class="hide"><h3 id="profit_margin" class="mb-0 mt-0"></h3></td>
                </tr>
            </table>
            @endcomponent
        </div>
    </div>
    @endcan
    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['class' => 'box-solid'])
                @include('report.partials.stock_report_table')
            @endcomponent
        </div>
    </div>
</section>
<!-- /.content -->

@endsection

@section('javascript')
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
@endsection