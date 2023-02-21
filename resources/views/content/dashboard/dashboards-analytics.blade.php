@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>
@endsection

@section('content')

<div class="row">
  {{-- <div class="col-lg-6 mb-4 order-0">
    <div class="card">
      <div class="d-flex align-items-end row">
        <div class="col-sm-7">
          <div class="card-body">
            <h5 class="card-title text-primary">Congratulations John! 🎉</h5>
            <p class="mb-4">You have done <span class="fw-bold">72%</span> more sales today. Check your new badge in your profile.</p>

            <a href="javascript:;" class="btn btn-sm btn-outline-primary">View Badges</a>
          </div>
        </div>
        <div class="col-sm-5 text-center text-sm-left">
          <div class="card-body pb-0 px-0 px-md-4">
            <img src="{{asset('assets/img/illustrations/man-with-laptop-light.png')}}" height="140" alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png">
          </div>
        </div>
      </div>
    </div>
  </div> --}}
  <div class="col-lg-12 col-md-4 order-1">
    <div class="row">
      <div class="col-lg-3 col-md-12 col-6 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex pb-1">
              <div class="col-lg-9">
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                  <div class="me-2">
                    <small style="font-size:75%;">EARNINGS (DAILY)</small>
                  </div>
                  <div class="user-progress d-flex align-items-center gap-1">
                    <h3 class="mb-0">0000</h3>
                  </div>
                </div>
              </div>
              <div class="col-lg-3" style="float:right;">
                <div class="avatar avatar-md flex-shrink-0 me-3">
                  <span class="avatar-initial rounded bg-label-info"><i class="bx bx-time bx-sm"></i></span>
                </div>    
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-12 col-6 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex pb-1">
              <div class="col-lg-9">
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                  <div class="me-2">
                    <small style="font-size:75%;">EARNINGS (MONTHLY)</small>
                  </div>
                  <div class="user-progress d-flex align-items-center gap-1">
                    <h3 class="mb-0">0000</h3>
                  </div>
                </div>
              </div>
              <div class="col-lg-3" style="float:right;">
                <div class="avatar avatar-md flex-shrink-0 me-3">
                  <span class="avatar-initial rounded bg-label-info"><i class="bx bx-wallet bx-sm"></i></span>
                </div>    
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-12 col-6 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex pb-1">
              <div class="col-lg-9">
                <div class="w-100 flex-wrap">
                  <div class="me-2 mb-2">
                    <small style="font-size:75%;">ONLINE</small>
                  </div>
                  <div class="user-progress d-flex align-items-center gap-1">
                    <h3 class="mb-0">{{ $hotspots_online }} %</h3>
                  </div>
                </div>
              </div>
              <div class="col-lg-3" style="float:right;">
                <div class="avatar avatar-md flex-shrink-0 me-3">
                  <span class="avatar-initial rounded bg-label-info"><i class="bx bx-plug bx-sm"></i></span>
                </div>      
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-12 col-6 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex pb-1">
              <div class="col-lg-9">
                <div class="w-100 flex-wrap">
                  <div class="me-2 mb-2">
                    <small style="font-size:75%;">HOTSPOTS</small>
                  </div>
                  <div class="user-progress d-flex align-items-center gap-1">
                    <h3 class="mb-0" id="total_hotspots">{{ count($hotspots) }}</h3>
                  </div>
                </div>
              </div>
              <div class="col-lg-3" style="float:right;">
                <div class="avatar avatar-md flex-shrink-0 me-3">
                  <span class="avatar-initial rounded bg-label-info"><i class="bx bx-server bx-sm"></i></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<div class="row">
  <!-- Expense Overview -->
  <div class="col-lg-6 order-1 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="card-header m-0 me-2 pb-3">Monthly Rewards</h5>
      </div>
      <div class="card-body px-0">
        <div class="tab-content p-0">
          <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
            <div class="d-flex p-4 pt-3">
              <div class="avatar flex-shrink-0 me-3">
                <img src="{{asset('assets/img/icons/unicons/wallet.png')}}" alt="User">
              </div>
              <div>
                <small class="text-muted d-block">Total Rewards</small>
                <div class="d-flex align-items-center">
                  <h6 class="mb-0 me-1" id="total_rewards"></h6>
                </div>
              </div>
            </div>
            <div id="incomeChart"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-6 order-1 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="card-header m-0 me-2 pb-3">Recent Activity</h5>
      </div>
      <div class="card-body px-0">
        <div class="tab-content p-0">
          <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
            <div class="d-flex p-4 pt-3">
              <div class="avatar flex-shrink-0 me-3">
                <img src="{{asset('assets/img/icons/unicons/wallet.png')}}" alt="User">
              </div>
              <div>
                <small class="text-muted d-block">Recent Activity</small>
                <div class="d-flex align-items-center">
                  {{-- <h6 class="mb-0 me-1" id="total_rewards"></h6> --}}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Expense Overview -->
</div>


<div class="row">
  <!-- Total Revenue -->
  <div class="col-12 order-2 order-md-3 order-lg-2 mb-4">
    <div class="card">
      <div class="row row-bordered g-0">
        <h5 class="card-header m-0 me-2 pb-3">Hotspots Status</h5>
        <div class="card-body">
          <div class="table-responsive text-nowrap">
            <table class="table" id="hotspot_status_table">
                <thead>
                    <tr>
                        <th data-sortable="true">Name</th>
                        <th data-sortable="true">City</th>
                        <th data-sortable="true">State</th>
                        <th data-sortable="true">Country</th>
                        <th data-sortable="true">Status</th>                        
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($hotspots as $hotspot)
                        <tr>
                            <td>{{ $hotspot->name }}</td>
                            <td>{{ $hotspot->city }}</td>
                            <td>{{ $hotspot->state }}</td>
                            <td>{{ $hotspot->country }}</td>
                            <td>{{ $hotspot->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>

var monthlyEarning = <?php echo json_encode($monthlyEarning);?>;


  // $(document).ready(function(){
  //   var url = 'api/getHotspots/?id=' + {{ Auth::user()->id }}

  //   $.ajax({
  //       type: "GET",
  //       url: url,
  //       headers: {
  //         'Content-Type': 'application/json'
  //       },
  //       success: function (result, status, error) {
  //         console.log("result : ", result);
  //         initPage(result);
  //       },
  //       error: function (xhr, status, error) {
  //           alert("Result: " + status + " " + error + " " + xhr.status + " " + xhr.statusText)
  //       }
  //   });
  // });

  // function initPage(hotspots){
  //   $("#total_hotspots").text(hotspots.length);

  //   hotspots.forEach(function(hotspot){
  //     var url = 'https://api.helium.io/v1/hotspots/' + hotspot.address + '/rewards';

  //     var hostspotsData = [];

  //     // Creating Our XMLHttpRequest object 
  //     var xhr = new XMLHttpRequest();
    
  //     // Making our connection  
  //     // var url = 'https://api.helium.io/v1/hotspots/' + hotspot_address;
  //     xhr.open("GET", url, true);

  //     // function execute after request is successful 

  //     fetch(url, { method: 'GET' })
  //       .then(Result => Result.json())
  //       .then(response => {
  //           console.log(response);
  //       })
  //       .catch(errorMsg => { console.log(errorMsg); });

  //   });


    
  // }

  // function initHotspots(data){

  // }

</script>
@endsection
