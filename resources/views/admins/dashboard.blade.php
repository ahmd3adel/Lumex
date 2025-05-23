@extends('layouts.index')

@section('title', trans('Dashboard'))

@section('breadcramp')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ trans('Admin Dashboard') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{ trans('Home') }}</a></li>
                        <li class="breadcrumb-item active">{{ trans('Dashboard') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('transferswidgs')
<div class="row mr-0 ml-0">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">outgoing transfer</span>
                <span class="info-box-number">10<small>%</small></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">incoming transfer</span>
                <span class="info-box-number">41,410</span>
            </div>
        </div>
    </div>
    <div class="clearfix hidden-md-up"></div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">unclaimed transfer</span>
                <span class="info-box-number">760</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">pending transfer</span>
                <span class="info-box-number">2,000</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('charts')
<div class="row ml-0 mr-0">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Monthly Recap Report</h5>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown"><i class="fas fa-wrench"></i></button>
                        <div class="dropdown-menu dropdown-menu-right" role="menu">
                            <a href="#" class="dropdown-item">Action</a>
                            <a href="#" class="dropdown-item">Another action</a>
                            <a href="#" class="dropdown-item">Something else here</a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">Separated link</a>
                        </div>
                    </div>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-center"><strong>Sales: 1 Jan, 2014 - 30 Jul, 2014</strong></p>
                        <div class="chart">
                            <canvas id="salesChart" height="180" style="height: 180px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-sm-4 col-12">
                        <div class="description-block border-right">
                            <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span>
                            <h5 class="description-header">$35,210.43</h5>
                            <span class="description-text">eur account</span>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="description-block border-right">
                            <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>
                            <h5 class="description-header">$10,390.90</h5>
                            <span class="description-text">lira account</span>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="description-block border-right">
                            <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>
                            <h5 class="description-header">$24,813.53</h5>
                            <span class="description-text">Dollar account</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('CurrenciesAccounts')
<div class="row mr-0 ml-0">
    @php
        $items = [
            ['color' => 'info', 'icon' => 'far fa-bookmark', 'title' => 'Bookmarks'],
            ['color' => 'success', 'icon' => 'far fa-thumbs-up', 'title' => 'Likes'],
            ['color' => 'warning', 'icon' => 'far fa-calendar-alt', 'title' => 'Events'],
            ['color' => 'danger', 'icon' => 'fas fa-comments', 'title' => 'Comments'],
        ];
    @endphp

    @foreach($items as $item)
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-{{ $item['color'] }}">
                <span class="info-box-icon"><i class="{{ $item['icon'] }}"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ $item['title'] }}</span>
                    <span class="info-box-number">41,410</span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 70%"></div>
                    </div>
                    <span class="progress-description">70% Increase in 30 Days</span>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@section('lastTransfers')
<div class="row mr-0 ml-0">
    @for ($i = 0; $i < 2; $i++)
    <div class="col-md-6 {{ $i == 0 ? 'pl-4' : 'pr-4' }}">
        <div class="card">
            <div class="card-header border-transparent">
                <h3 class="card-title">Latest Orders</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Item</th>
                                <th>Status</th>
                                <th>Popularity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><a href="#">OR9842</a></td>
                                <td>Call of Duty IV</td>
                                <td><span class="badge badge-success">Shipped</span></td>
                                <td><div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div></td>
                            </tr>
                            <tr>
                                <td><a href="#">OR1848</a></td>
                                <td>Samsung Smart TV</td>
                                <td><span class="badge badge-warning">Pending</span></td>
                                <td><div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div></td>
                            </tr>
                            <tr>
                                <td><a href="#">OR7429</a></td>
                                <td>iPhone 6 Plus</td>
                                <td><span class="badge badge-danger">Delivered</span></td>
                                <td><div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer clearfix">
                <a href="#" class="btn btn-sm btn-info float-left">Place New Order</a>
                <a href="#" class="btn btn-sm btn-secondary float-right">View All Orders</a>
            </div>
        </div>
    </div>
    @endfor
</div>
</div> <!-- Close content-wrapper -->
@endsection
