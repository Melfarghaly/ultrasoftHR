@extends('layouts.app')
@section('title', __('lang_v1.opening_balance'))
@php
    $api_key = env('GOOGLE_MAP_API_KEY');
@endphp
@if (!empty($api_key))
    @section('css')
        @include('contact.partials.google_map_styles')
    @endsection
@endif
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black"> @lang('lang_v1.opening_balance')
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      
       
           
            @if (auth()->user()->can('supplier.view') ||
                    auth()->user()->can('customer.view') ||
                    auth()->user()->can('supplier.view_own') ||
                    auth()->user()->can('customer.view_own'))
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="example_table">
                        <thead>
                            <tr>
                                <th>@lang('messages.action')</th>
                                    <th>@lang('lang_v1.contact_id')</th>
                                    <th>@lang('contact.name')</th>
                                    <th>@lang('contact.tax_no')</th>
                                    <th>@lang('account.opening_balance')</th>
                            </tr>
                        </thead>
                       
                        <tfoot>
                            <tr class="bg-gray font-17 text-center footer-total">
                                <td colspan="4" class="text-right"></td>
                                <td>
                                    <button class="btn btn-primary" id="edit-opening-balance-btn">تحديث</button>
                                </td>
                            </tr>
                        </tfoot>
                        <tbody id="example-table-body">

                        </tbody>
                    </table>
                </div>
            @endif
       <!-- Modal for Editing Opening Balance -->
<div class="modal fade" id="editOpeningBalanceModal" tabindex="-1" role="dialog" aria-labelledby="editOpeningBalanceLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editOpeningBalanceLabel">تحديث الرصيد الافتتاحي لكل العملاء</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit-opening-balance-form" action="{{route('updateAllOpeningBalance')}}" method="POST">
                @csrf
            <div class="modal-body">
                
                    <div class="form-group">
                        <label for="openingBalance">الرصيد الافتتاحي</label>
                        <input type="number" name="openingBalance" class="form-control" id="openingBalance" placeholder="ادخل الرصيد الافتتاحي للكل" required>
                    </div>
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                <button type="submit" class="btn btn-primary" id="save-opening-balance">تحديث </button>
            </div>
        </form>
        </div>
    </div>
</div>


        <div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>
        <div class="modal fade pay_contact_due_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>

    </section>
    <!-- /.content -->
@stop

@section('javascript')
<script>
    document.getElementById('edit-opening-balance-btn').addEventListener('click', function() {
        // Clear the input field and prepare the modal
        const openingBalanceInput = document.getElementById('openingBalance');
        openingBalanceInput.value = ''; // Clear any previous value
        
        // Show the modal using Bootstrap's modal method
        $('#editOpeningBalanceModal').modal('show');
    });

    document.getElementById('save-opening-balance').addEventListener('click', function() {
        const newOpeningBalance = document.getElementById('openingBalance').value;

        // Here you will need to send the new opening balance to the server
        // For example, using an AJAX call to save it
        
        console.log('New Opening Balance:', newOpeningBalance);

        // Close the modal
        $('#editOpeningBalanceModal').modal('hide');

        // Optionally, you can refresh the table or update the display with the new balance
    });
</script>

<script type="text/javascript">

    $(function() {
        let url = "{{route('getOpeningBalance')}}";
        // create a datatable
        $('#example_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: url,
            "order": [[ 0, "desc" ]],
            columns: [
                { data: 'action'},
                { data: 'contact_id'},
                { data: 'name'},
                { data: 'tax_number'},
                { data: 'opening_balance'},
              
            ],
    
        });
    });
    
    
    function reloadTable()
    {
        /*
            reload the data on the datatable
        */
        $('#example_table').DataTable().ajax.reload();
    }
    
    </script>
    @if (!empty($api_key))
        <script>
            // This example adds a search box to a map, using the Google Place Autocomplete
            // feature. People can enter geographical searches. The search box will return a
            // pick list containing a mix of places and predicted search terms.

            // This example requires the Places library. Include the libraries=places
            // parameter when you first load the API. For example:
            // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

            function initAutocomplete() {
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: {
                        lat: -33.8688,
                        lng: 151.2195
                    },
                    zoom: 10,
                    mapTypeId: 'roadmap'
                });

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                        map.setCenter(initialLocation);
                    });
                }


                // Create the search box and link it to the UI element.
                var input = document.getElementById('shipping_address');
                var searchBox = new google.maps.places.SearchBox(input);
                map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

                // Bias the SearchBox results towards current map's viewport.
                map.addListener('bounds_changed', function() {
                    searchBox.setBounds(map.getBounds());
                });

                var markers = [];
                // Listen for the event fired when the user selects a prediction and retrieve
                // more details for that place.
                searchBox.addListener('places_changed', function() {
                    var places = searchBox.getPlaces();

                    if (places.length == 0) {
                        return;
                    }

                    // Clear out the old markers.
                    markers.forEach(function(marker) {
                        marker.setMap(null);
                    });
                    markers = [];

                    // For each place, get the icon, name and location.
                    var bounds = new google.maps.LatLngBounds();
                    places.forEach(function(place) {
                        if (!place.geometry) {
                            console.log("Returned place contains no geometry");
                            return;
                        }
                        var icon = {
                            url: place.icon,
                            size: new google.maps.Size(71, 71),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(17, 34),
                            scaledSize: new google.maps.Size(25, 25)
                        };

                        // Create a marker for each place.
                        markers.push(new google.maps.Marker({
                            map: map,
                            icon: icon,
                            title: place.name,
                            position: place.geometry.location
                        }));

                        //set position field value
                        var lat_long = [place.geometry.location.lat(), place.geometry.location.lng()]
                        $('#position').val(lat_long);

                        if (place.geometry.viewport) {
                            // Only geocodes have viewport.
                            bounds.union(place.geometry.viewport);
                        } else {
                            bounds.extend(place.geometry.location);
                        }
                    });
                    map.fitBounds(bounds);
                });
            }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key={{ $api_key }}&libraries=places" async defer></script>
        <script type="text/javascript">
            $(document).on('shown.bs.modal', '.contact_modal', function(e) {
                initAutocomplete();
            });
        </script>
    @endif
@endsection
