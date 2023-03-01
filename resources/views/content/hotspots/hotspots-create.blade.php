@extends('layouts/contentNavbarLayout')

@section('title', ' Hotspots - Add Hotspot')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Hotspots /</span> Add Hotspot</h4>

    <!-- Basic Layout -->
    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Add Hotspot</h5> <small class="text-muted float-end"></small>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('errors'))
                        <div class="alert alert-danger" role="alert">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    <form id="createhotspot" class="mb-3" action="{{ url('/hotspots/store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="hotspot-address">Address</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="hotspot-address" name="address"
                                    placeholder="Address Key" required/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="fill_auto_address"></label>
                            <div class="col-sm-10">
                                <button type="button" class="btn btn-primary" id="fill_auto_address">Fill automatically</button>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="hotspot-name">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="hotspot-name" name="name"
                                    placeholder="Name" readonly required/>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="hotspot-city">City</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="hotspot-city" name="city"
                                    placeholder="City" readonly required/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="hotspot-state">State</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="hotspot-state" name="state"
                                    placeholder="State" readonly required/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="hotspot-country">Country</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="hotspot-country" name="country"
                                    placeholder="Country" readonly required/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="hotspot-owner">Owner</label>
                            <div class="btn-group col-sm-10">
                                <select class="form-select form-select-md" name="owner_id">
                                    <option selected>Select Owner</option>
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="hotspot-percentage">Percentage</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="hotspot-percentage" name="percentage"
                                    placeholder="Percentage" min="0" max="100" required/>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Add Hotspot From CSV file</h5> <small class="text-muted float-end"></small>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-10">
                            <button type="button" class="btn btn-primary" id="from_csv_btn">Import</button>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Importing LOG</label>
                        <textarea class="form-control" id="log_view" rows="3" readonly style="min-height: 200px;"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script type="module">
        var token = '{{ csrf_token() }}';

        /*---- Functions to convert CSV to JSON ----*/
        function processData(csv) {
            var lines=csv.split("\r\n");
            var result = [];

            // NOTE: If your columns contain commas in their values, you'll need
            // to deal with those before doing the next step 
            // (you might convert them to &&& or something, then covert them back later)
            // jsfiddle showing the issue https://jsfiddle.net/
            var headers=lines[0].split(",");

            for(var i=1;i<lines.length;i++){
                if(lines[i] == "")
                    continue;

                var obj = {};
                var currentline=lines[i].split(",");

                for(var j=0;j<headers.length;j++){
                    obj[headers[j]] = currentline[j];
                }

                result.push(obj);

            }

            //return result; //JavaScript object
            return result; //JSON
        }

        function getDataFromAPI(jsonCSV) {
            var sendData = []

            for(var i = 0 ; i < jsonCSV.length; i ++) {
                var dataTemp = {}
                var line = jsonCSV[i];
                dataTemp['email'] = line['user'];
                dataTemp['percentage'] = line['percentage'];
                dataTemp['address'] = line['address'];

                sendData.push(dataTemp);
            }

            console.log(sendData);

            $.ajax({
                type:'post',
                url:'/api/addHotspotsByCSV',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Authorization': 'Bearer {{Auth::user()->api_token}}'
                },
                data : {
                    'data' : sendData
                },
                success:function(resp) {
                    console.log(resp);
                }
            });
        }
        $(document).ready(function(){
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#from_csv_btn').on('click', function() {
                var input = document.createElement('input');
                input.type = 'file';

                input.onchange = e => {
                    var file = e.target.files[0];

                    if(file.type != 'text/csv') {
                        alert('It is not CSV file format!');
                        return;
                    }
                    
                    /*-------- Parse CSV data -----------*/

                    var reader = new FileReader();
                    reader.readAsText(file, 'UTF-8');

                    reader.onload = readerEvent => {
                        var content = readerEvent.target.result;
                        
                        var jsonCSV = processData(content);

                        var data = getDataFromAPI(jsonCSV);
                    }
                }

                input.click();
            })
        });
    </script>

@endsection
