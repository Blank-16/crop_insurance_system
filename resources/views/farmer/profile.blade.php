<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Complete Farmer Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('farmer.profile.store') }}">
                    @csrf
                    <div class="mb-4">
                        <x-input-label for="land_size" :value="__('Land Size (e.g. 5 Acres)')" />
                        <x-text-input id="land_size" class="block mt-1 w-full" type="text" name="land_size" :value="old('land_size', $profile->land_size ?? '')" required autofocus />
                        <x-input-error :messages="$errors->get('land_size')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="crop_type" :value="__('Main Crop Type')" />
                        <x-text-input id="crop_type" class="block mt-1 w-full" type="text" name="crop_type" :value="old('crop_type', $profile->crop_type ?? '')" required />
                        <x-input-error :messages="$errors->get('crop_type')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="region" :value="__('Farming Region')" />
                        <x-text-input id="region" class="block mt-1 w-full" type="text" name="region" :value="old('region', $profile->region ?? '')" required />
                        <x-input-error :messages="$errors->get('region')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label :value="__('Farm Location (Pin on Map)')" />
                        <div id="farm-map" style="height: 300px; width: 100%; border-radius: 0.5rem; margin-top: 0.5rem; z-index: 10;"></div>
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $profile->latitude ?? '') }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $profile->longitude ?? '') }}">
                        <p class="text-xs text-gray-500 mt-1">Click on the map to set your farm's exact coordinates.</p>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button>
                            {{ __('Save Profile') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Leaflet Map Integration -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var latInput = document.getElementById('latitude');
            var lngInput = document.getElementById('longitude');
            
            // Default center (e.g., India center)
            var startLat = latInput.value ? parseFloat(latInput.value) : 20.5937;
            var startLng = lngInput.value ? parseFloat(lngInput.value) : 78.9629;
            var zoom = latInput.value ? 13 : 5;

            var map = L.map('farm-map').setView([startLat, startLng], zoom);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var marker;
            if (latInput.value && lngInput.value) {
                marker = L.marker([startLat, startLng]).addTo(map);
            }

            map.on('click', function(e) {
                var lat = e.latlng.lat;
                var lng = e.latlng.lng;
                
                latInput.value = lat;
                lngInput.value = lng;

                if (marker) {
                    map.removeLayer(marker);
                }
                marker = L.marker([lat, lng]).addTo(map);
            });
        });
    </script>
</x-app-layout>
