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
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex flex-col items-center justify-center">
                    <h4 class="text-gray-500 font-medium mb-2">Claim Resolution</h4>
                    <div class="w-full bg-gray-200 rounded-full h-4 mb-1">
                        <div class="bg-green-500 h-4 rounded-full" style="width: {{ $approvedPercentage }}%"></div>
                    </div>
                    <div class="flex justify-between w-full text-xs text-gray-600 mt-1">
                        <span>{{ $approvedPercentage }}% Approved</span>
                        <span>{{ $rejectedPercentage }}% Rejected</span>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                    <h4 class="text-gray-500 font-medium text-sm">Most Claimed Crop</h4>
                    @if($mostClaimedCrop)
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $mostClaimedCrop->crop_type }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ $mostClaimedCrop->total_claims }} Claims filed</p>
                    @else
                        <p class="text-xl font-medium text-gray-400 mt-2">No Claim Data</p>
                    @endif
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 text-center flex flex-col justify-center text-indigo-600 font-semibold hover:bg-indigo-50 transition cursor-pointer">
                    <svg class="w-8 h-8 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    View Full Audit Logs
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
