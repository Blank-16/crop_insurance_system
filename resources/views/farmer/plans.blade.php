<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Browse Insurance Plans') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-alert />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 py-4 px-6">
                <form id="filter-form" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="w-full md:w-1/3">
                        <label for="crop_type" class="block text-sm font-medium text-gray-700">Crop Type</label>
                        <input type="text" name="crop_type" id="crop_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="e.g. Wheat">
                    </div>
                    <div class="w-full md:w-1/3">
                        <label for="region" class="block text-sm font-medium text-gray-700">Region</label>
                        <input type="text" name="region" id="region" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="e.g. North">
                    </div>
                    <div class="w-full md:w-1/3 flex items-center">
                        <button type="button" onclick="fetchPlans()" class="w-full md:w-auto inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Filter
                        </button>
                        <button type="button" onclick="clearFilters()" class="ml-2 inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Clear
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg relative">
                <!-- Loading overlay -->
                <div id="loading" class="hidden absolute inset-0 bg-white bg-opacity-75 z-10 flex items-center justify-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                </div>

                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="text-lg font-bold">Available Plans</h3>
                        
                        <form id="compare-form" action="{{ route('farmer.plans.compare') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2 px-4 rounded transition">
                                Compare Selected
                            </button>
                        </form>
                    </div>
                    
                    <div id="plans-container">
                        @include('farmer.partials.plans_list')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function fetchPlans() {
            const cropType = document.getElementById('crop_type').value;
            const region = document.getElementById('region').value;
            const loading = document.getElementById('loading');
            const container = document.getElementById('plans-container');

            loading.classList.remove('hidden');

            axios.get('{{ route('farmer.plans') }}', {
                params: {
                    crop_type: cropType,
                    region: region
                },
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                container.innerHTML = response.data.html;
            })
            .catch(error => {
                console.error("Error fetching plans", error);
                alert('Something went wrong while filtering.');
            })
            .finally(() => {
                loading.classList.add('hidden');
            });
        }

        function clearFilters() {
            document.getElementById('crop_type').value = '';
            document.getElementById('region').value = '';
            fetchPlans();
        }

        // Handle pagination link clicks
        document.addEventListener('click', function (e) {
            if (e.target.tagName.toLowerCase() === 'a' && e.target.closest('.pagination')) {
                e.preventDefault();
                const url = e.target.getAttribute('href');
                
                const loading = document.getElementById('loading');
                const container = document.getElementById('plans-container');
                loading.classList.remove('hidden');

                axios.get(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    container.innerHTML = response.data.html;
                    window.scrollTo(0, 0);
                })
                .catch(error => console.error("Error fetching page:", error))
                .finally(() => {
                    loading.classList.add('hidden');
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
