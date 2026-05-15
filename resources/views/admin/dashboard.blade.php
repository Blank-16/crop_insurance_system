<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-alert />

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <x-card title="Total Users" :value="$usersCount" color="blue" />
                <x-card title="Total Plans" :value="$plansCount" color="purple" />
                <x-card title="Total Policies" :value="$policiesCount" color="green" />
                <x-card title="Total Claims" :value="$claimsCount" color="red" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Claim Resolution Chart -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100" x-data="{
                    init() {
                        let options = {
                            series: {{ json_encode($chartData['status_series']) }},
                            chart: { type: 'donut', height: 250 },
                            labels: {!! json_encode($chartData['status_labels']) !!},
                            colors: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444'],
                            plotOptions: { pie: { donut: { size: '65%' } } },
                            dataLabels: { enabled: false },
                            legend: { position: 'bottom' }
                        };
                        let chart = new window.ApexCharts(this.$refs.statusChart, options);
                        chart.render();
                    }
                }">
                    <h4 class="text-gray-500 font-medium mb-4 text-sm uppercase tracking-wider">Claim Status Distribution</h4>
                    <div x-ref="statusChart" class="flex justify-center"></div>
                </div>
                
                <!-- Crop Distribution Chart -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100" x-data="{
                    init() {
                        let options = {
                            series: [{ name: 'Policies', data: {{ json_encode($chartData['crop_series']) }} }],
                            chart: { type: 'bar', height: 250, toolbar: { show: false } },
                            plotOptions: { bar: { borderRadius: 4, horizontal: true } },
                            dataLabels: { enabled: false },
                            xaxis: { categories: {!! json_encode($chartData['crop_labels']) !!} },
                            colors: ['#8B5CF6']
                        };
                        let chart = new window.ApexCharts(this.$refs.cropChart, options);
                        chart.render();
                    }
                }">
                    <h4 class="text-gray-500 font-medium mb-4 text-sm uppercase tracking-wider">Policies by Crop Type</h4>
                    <div x-ref="cropChart"></div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div>
                        <h4 class="text-gray-500 font-medium text-sm uppercase tracking-wider">Most Claimed Crop</h4>
                        @if($mostClaimedCrop)
                            <p class="text-4xl font-bold text-gray-800 mt-4">{{ $mostClaimedCrop->crop_type }}</p>
                            <p class="text-sm text-gray-500 mt-2 flex items-center">
                                <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                                {{ $mostClaimedCrop->total_claims }} Claims filed
                            </p>
                        @else
                            <p class="text-xl font-medium text-gray-400 mt-2">No Claim Data</p>
                        @endif
                    </div>
                    
                    <a href="{{ route('admin.users') }}" class="mt-6 w-full text-center bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-medium py-3 px-4 rounded-lg transition duration-150 ease-in-out border border-indigo-200">
                        View System Users
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Recent Claims Overview</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farmer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentClaims as $claim)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ $claim->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $claim->policy->farmer->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $claim->policy->plan->crop_type ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <x-status-badge :status="$claim->status" />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">No recent claims found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
