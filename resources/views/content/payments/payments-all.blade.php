@extends('layouts/contentNavbarLayout')

@section('title', 'Payments - All Payments')

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Payments /</span> All Payments
    </h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">All Payments</h5> <small class="text-muted float-end"></small>
      </div>
      <div class="card-body">

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('errors'))
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>During</th>
                        <th>Amount</th>
                        <th>Random String</th>
                        <th>Status</th>
                        <th>Paid at</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($payments as $payment)
                        <tr>
                            <td>{{ $payment->during }}</td>
                            <td>{{ $payment->amount }}</td>
                            <td>{{ $payment->random }}</td>
                            <td>
                                @if($payment->status_id == 2)
                                    <span class="badge bg-info">Paid</span>
                                @else
                                    <span class="badge bg-warning">Not Paid</span>
                                @endif
                            </td>
                            <td>{{ $payment->paid_at }}</td>
                            <td>
                                @if($payment->status_id == 1)
                                    {{-- <form action="{{ url('/payment/markpaid') }}" method="POST"> --}}
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $payment->id }}" class="btn btn-primary">Mark Paid</button>
                                    {{-- </form> --}}
                                    
                                    <!-- Modal -->
                                    <div class="modal fade" id="paymentModal{{ $payment->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <form method="POST" action="{{ route('payment-markpaid', $payment->id) }}">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $payment->id }}">
                                            
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel1">Payment</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col mb-3 text-center">
                                                            <img src="images/default.png" alt="Your QR Code will be show here..." id="qrcode{{$payment->id}}">    
                                                        </div>
                                                    </div>
                                                    
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Paid</button>
                                                    </div>
                                                </div>
                                            </form>

                                            <script>
                                                var data = "{ type:payment, " + 
                                                            "utf8Memo: true," + 
                                                            "address: 14khJcm9tpNgeSUr4D7zvbZ5zpzMxwkc3vC6GiGZRy2sSZxp4V5," + 
                                                            "amount:" + "<?php echo $payment->amount; ?>," + 
                                                            "memo:" + "<?php echo $payment->random; ?> }";

                                                var url = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${data}`;
                                                var code = document.querySelector('#qrcode{{$payment->id}}');
                                                code.src = url;
                                            </script>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
      </div>
    </div>
    <!--/ Basic Bootstrap Table -->
    <!--/ Responsive Table -->
@endsection
