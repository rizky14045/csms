@extends('admin.layout.app')
@section('styles')
<style>
    #map { height: 500px; width: 100%; }
</style>
@stop
@section('content')
    
<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Dashboard</h4>
    </div>
</div>

<!-- start row -->
<div class="row">
    <div class="col-md-12 col-xl-12">
       <div class="card">
            <div class="card-body">
                <label for="" class="form-label">Cari Data</label>
                <div class="d-flex gap-3">

                    <div class="mb-3 col-md-3">
                        <input type="date" class="form-control d-inline" id="exampleFormControlInput1">
                    </div>
                    <div class="button-search">
                        <button type="button" class="btn btn-primary d-inline">Cari</button>
                    </div>
                </div>
            </div>
       </div>
        <div class="card">
            <div class="card-body">
                <div id="map"></div>
            </div>
        </div>
    </div> <!-- end sales -->
</div> <!-- end row -->
@endsection
@section('scripts')
<script>
    // Step 1: Initialize the map
    const map = L.map('map').setView([-2.548926, 118.0148634], 5);
  
    // Step 2: Add a base layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 18,
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
  
    // Step 3: Define custom icon
    const customIcon = L.icon({
      iconUrl: 'https://upload.wikimedia.org/wikipedia/commons/e/ec/RedDot.svg', // URL gambar icon
      iconSize: [20, 20],
      iconAnchor: [10, 10],
      popupAnchor: [0, -10]
    });
  
    // Step 4: Add detailed data points
    const detailedDataPoints = [
      {
        lat: -6.2088, 
        lng: 106.8456, 
        name: "Jakarta", 
        description: "Ibu kota Indonesia, pusat ekonomi dan pemerintahan."
      },
      {
        lat: -7.7956, 
        lng: 110.3695, 
        name: "Yogyakarta", 
        description: "Kota budaya dan sejarah, terkenal dengan Candi Borobudur dan Prambanan."
      },
      {
        lat: -6.9147, 
        lng: 107.6098, 
        name: "Bandung", 
        description: "Dikenal sebagai Kota Kembang, pusat pendidikan dan wisata."
      },
      {
        lat: -5.1477, 
        lng: 119.4327, 
        name: "Makassar", 
        description: "Kota terbesar di Sulawesi Selatan, terkenal dengan kuliner seafood-nya."
      }
    ];
  
    // Loop through each data point and add to the map with hover effect
    detailedDataPoints.forEach(point => {
      const marker = L.marker([point.lat, point.lng], { icon: customIcon }).addTo(map);
  
      // Create the popup content
      const popupContent = `
        <b>${point.name}</b><br>
        ${point.description}
      `;
  
      // Bind events for hover
      marker.on('mouseover', function() {
        this.bindPopup(popupContent).openPopup(); // Open popup on hover
      });
  
      marker.on('mouseout', function() {
        this.closePopup(); // Close popup when hover ends
      });
    });
  </script>
@endsection

