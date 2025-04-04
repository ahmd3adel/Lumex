@extends('layouts.index')
@section('title' , $pageTitle)
@section('breadcramp')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid ">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="">{{trans('clients')}}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        @parent
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{trans('Home')}}</a></li>
                            <li class="breadcrumb-item active "> {{trans('products table')}} </li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
@endsection

@section('content')
            <div class="content-wrapper m-0">
                <!-- Content Header (Page header) -->

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header p-2">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Activity</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Timeline</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                                        </ul>
                                    </div><!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="active tab-pane" id="activity">
                                                <!-- Transaction Post -->
                                                <div class="post">
                                                    <div class="user-block">
                                                        <img class="img-circle img-bordered-sm" src="img/transaction-icon.png" alt="transaction icon">
                                                        <span class="username">
                                <a href="#">Invoice #INV-12345</a>
                                <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                            </span>
                                                        <span class="description">Paid by Client - 2:30 PM today</span>
                                                    </div>
                                                    <!-- /.user-block -->
                                                    <p>
                                                        Invoice #INV-12345 for $1,250.00 was marked as paid by **Client Name**.
                                                    </p>

                                                    <p>
                                                        <a href="#" class="link-black text-sm mr-2"><i class="fas fa-eye mr-1"></i> View Invoice</a>
                                                        <a href="#" class="link-black text-sm"><i class="fas fa-download mr-1"></i> Download Receipt</a>
                                                    </p>
                                                </div>
                                                <!-- /.post -->

                                                <!-- Overdue Alert Post -->
                                                <div class="post clearfix">
                                                    <div class="user-block">
                                                        <img class="img-circle img-bordered-sm" src="img/alert-icon.png" alt="alert icon">
                                                        <span class="username">
                                <a href="#">Overdue Invoice Alert</a>
                                <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                            </span>
                                                        <span class="description">Generated - 1:00 PM today</span>
                                                    </div>
                                                    <!-- /.user-block -->
                                                    <p>
                                                        Invoice #INV-12233 for $900.00 is overdue by 15 days. Contact the client to settle the payment.
                                                    </p>

                                                    <form class="form-horizontal">
                                                        <div class="input-group input-group-sm mb-0">
                                                            <input class="form-control form-control-sm" placeholder="Write a message to the client">
                                                            <div class="input-group-append">
                                                                <button type="submit" class="btn btn-danger">Send Reminder</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <!-- /.post -->

                                                <!-- Monthly Report Post -->
                                                <div class="post">
                                                    <div class="user-block">
                                                        <img class="img-circle img-bordered-sm" src="img/report-icon.png" alt="report icon">
                                                        <span class="username">
                                <a href="#">Monthly Report Available</a>
                                <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                            </span>
                                                        <span class="description">Generated - Jan 5, 2025</span>
                                                    </div>
                                                    <!-- /.user-block -->
                                                    <div class="row mb-3">
                                                        <div class="col-sm-6">
                                                            <img class="img-fluid" src="img/report-thumbnail.png" alt="Report Thumbnail">
                                                        </div>
                                                        <!-- /.col -->
                                                    </div>
                                                    <!-- /.row -->

                                                    <p>
                                                        The financial report for December 2024 is now available. Total revenue: $50,000.00, Net Profit: $12,000.00.
                                                    </p>

                                                    <p>
                                                        <a href="#" class="link-black text-sm mr-2"><i class="fas fa-download mr-1"></i> Download Report</a>
                                                        <a href="#" class="link-black text-sm"><i class="fas fa-eye mr-1"></i> View Details</a>
                                                    </p>
                                                </div>
                                                <!-- /.post -->
                                            </div>
                                            <!-- /.tab-pane -->
                                            <div class="tab-pane" id="timeline">
                                                <!-- The timeline -->
                                                <div class="timeline timeline-inverse">
                                                    <!-- timeline time label -->
                                                    <div class="time-label">
                            <span class="bg-danger">
                                15 Jan. 2025
                            </span>
                                                    </div>
                                                    <!-- /.timeline-label -->
                                                    <!-- timeline item -->
                                                    <div>
                                                        <i class="fas fa-file-invoice bg-primary"></i>

                                                        <div class="timeline-item">
                                                            <span class="time"><i class="far fa-clock"></i> 10:30 AM</span>

                                                            <h3 class="timeline-header"><a href="#">Invoice #INV-45678</a> was generated</h3>

                                                            <div class="timeline-body">
                                                                A new invoice for $5,250.00 was created for **Client Name**. Payment is due by 20 Jan. 2025.
                                                            </div>
                                                            <div class="timeline-footer">
                                                                <a href="#" class="btn btn-primary btn-sm">View Invoice</a>
                                                                <a href="#" class="btn btn-danger btn-sm">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END timeline item -->

                                                    <!-- timeline item -->
                                                    <div>
                                                        <i class="fas fa-money-check-alt bg-info"></i>

                                                        <div class="timeline-item">
                                                            <span class="time"><i class="far fa-clock"></i> 9:00 AM</span>

                                                            <h3 class="timeline-header border-0">Payment of $3,000 received from <a href="#">Client Name</a></h3>
                                                        </div>
                                                    </div>
                                                    <!-- END timeline item -->

                                                    <!-- timeline item -->
                                                    <div>
                                                        <i class="fas fa-chart-line bg-warning"></i>

                                                        <div class="timeline-item">
                                                            <span class="time"><i class="far fa-clock"></i> 8:45 AM</span>

                                                            <h3 class="timeline-header"><a href="#">Monthly Financial Report</a> was finalized</h3>

                                                            <div class="timeline-body">
                                                                The financial report for December 2024 shows a total revenue of $50,000.00 with a net profit of $12,000.00.
                                                            </div>
                                                            <div class="timeline-footer">
                                                                <a href="#" class="btn btn-warning btn-flat btn-sm">View Report</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END timeline item -->

                                                    <!-- timeline time label -->
                                                    <div class="time-label">
                            <span class="bg-success">
                                10 Jan. 2025
                            </span>
                                                    </div>
                                                    <!-- /.timeline-label -->

                                                    <!-- timeline item -->
                                                    <div>
                                                        <i class="fas fa-user-plus bg-purple"></i>

                                                        <div class="timeline-item">
                                                            <span class="time"><i class="far fa-clock"></i> 2:00 PM</span>

                                                            <h3 class="timeline-header"><a href="#">New Client</a> was added</h3>

                                                            <div class="timeline-body">
                                                                A new client, **Company ABC**, was added to the system with initial credit of $10,000.00.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END timeline item -->

                                                    <div>
                                                        <i class="far fa-clock bg-gray"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.tab-pane -->

                                            <div class="tab-pane" id="settings">
                                                <form class="form-horizontal">
                                                    <div class="form-group row">
                                                        <label for="inputName" class="col-sm-2 col-form-label">First Name</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="inputFirstName" placeholder="First Name">
                                                        </div>
                                                        <label for="inputLastName" class="col-sm-2 col-form-label">Last Name</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="inputLastName" placeholder="Last Name">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                                        <div class="col-sm-4">
                                                            <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                                                        </div>
                                                        <label for="inputAltEmail" class="col-sm-2 col-form-label">Alternate Email</label>
                                                        <div class="col-sm-4">
                                                            <input type="email" class="form-control" id="inputAltEmail" placeholder="Alternate Email">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="inputUsername" class="col-sm-2 col-form-label">Username</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="inputUsername" placeholder="Username">
                                                        </div>
                                                        <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                                                        <div class="col-sm-4">
                                                            <input type="password" class="form-control" id="inputPassword" placeholder="Password">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="inputPhone" class="col-sm-2 col-form-label">Phone</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="inputPhone" placeholder="Phone">
                                                        </div>
                                                        <label for="inputAltPhone" class="col-sm-2 col-form-label">Alternate Phone</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="inputAltPhone" placeholder="Alternate Phone">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="inputAddress" class="col-sm-2 col-form-label">Address Line 1</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="inputAddress1" placeholder="Address Line 1">
                                                        </div>
                                                        <label for="inputAddress2" class="col-sm-2 col-form-label">Address Line 2</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="inputAddress2" placeholder="Address Line 2">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="offset-sm-2 col-sm-10">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="offset-sm-2 col-sm-10">
                                                            <button type="submit" class="btn btn-danger">Submit</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- /.tab-pane -->
                                        </div>
                                        <!-- /.tab-content -->
                                    </div><!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
@endsection
