<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Claim Details') }} #{{ $claim->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Claim for Policy #{{ $claim->policy_id }}</h3>
                    <p class="text-gray-500 mt-1">Filed on {{ $claim->created_at->format('M d, Y g:i A') }}</p>
                </div>
                <div class="text-right">
                    <div class="mb-2"><x-status-badge :status="$claim->status" /></div>
                    @if($claim->status === 'Rejected' && $claim->remarks)
                        <p class="text-xs text-red-500 font-medium">Reason: {{ $claim->remarks }}</p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Claim Info -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                    <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Damage Report</h4>
                    <div class="space-y-4">
                        <div>
                            <span class="block text-sm text-gray-500">Damage Type</span>
                            <span class="font-medium text-gray-800 capitalize">{{ $claim->damage_type }}</span>
                        </div>
                        <div>
                            <span class="block text-sm text-gray-500">Damage Percentage</span>
                            <span class="font-medium text-red-600">{{ $claim->damage_percentage }}%</span>
                        </div>
                        <div>
                            <span class="block text-sm text-gray-500">Calculated Payout Amount</span>
                            <span class="font-bold text-xl text-green-600">${{ number_format($claim->calculated_amount, 2) }}</span>
                            <p class="text-xs text-gray-400 mt-1">Based on {{ $claim->policy->plan->coverage }} max coverage</p>
                        </div>
                        <div>
                            <span class="block text-sm text-gray-500">Farmer Description</span>
                            <p class="text-gray-700 bg-gray-50 p-3 rounded text-sm mt-1 border">{{ $claim->description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                    <h4 class="text-lg font-bold text-gray-800 mb-6 border-b pb-2">Processing Timeline</h4>
                    <div class="relative pl-4">
                        @if($claim->logs->isNotEmpty())
                            @foreach($claim->logs as $index => $log)
                                <div class="mb-6 relative">
                                    <div class="absolute w-3 h-3 bg-indigo-500 rounded-full mt-1.5 -left-[1.35rem]"></div>
                                    @if(!$loop->last)
                                        <div class="absolute w-0.5 h-full bg-gray-200 left-[-1.02rem] top-3"></div>
                                    @endif
                                    
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-800">{{ ucwords(str_replace('_', ' ', $log->status)) }}</span>
                                        <span class="text-xs text-gray-500">{{ $log->created_at->format('M d, Y - g:i A') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-500">No logs available.</p>
                        @endif
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
