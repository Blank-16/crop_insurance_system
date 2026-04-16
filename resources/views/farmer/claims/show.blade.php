<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Claim Details') }} #{{ $claim->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-start bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Claim #{{ $claim->id }}</h3>
                    <p class="text-gray-500 mt-1 text-sm">
                        Filed {{ $claim->created_at->diffForHumans() }}
                        &mdash; {{ $claim->created_at->format('M d, Y g:i A') }}
                    </p>
                    <p class="mt-2 text-sm text-gray-600">
                        Policy for <span class="font-semibold text-indigo-600">{{ $claim->policy->plan->crop_type ?? 'N/A' }}</span>
                        in <span class="font-semibold">{{ $claim->policy->plan->region ?? 'N/A' }}</span>
                    </p>
                </div>
                <div class="text-right">
                    <div class="mb-2"><x-status-badge :status="$claim->status" /></div>
                    @if($claim->status === 'Approved')
                        <p class="text-xs text-green-600 font-medium">Your claim was approved. Payout is being processed.</p>
                    @elseif($claim->status === 'Rejected')
                        <p class="text-xs text-red-500 font-medium">Your claim was not approved.</p>
                        @if($claim->remarks)
                            <p class="text-xs text-red-500 italic mt-1">Reason: {{ $claim->remarks }}</p>
                        @else
                            <p class="text-xs text-red-400 italic mt-1">No specific reason provided. Contact your provider.</p>
                        @endif
                    @elseif($claim->status === 'Submitted')
                        <p class="text-xs text-yellow-600 font-medium">Your claim is awaiting review by the provider.</p>
                    @elseif($claim->status === 'Under Review')
                        <p class="text-xs text-blue-600 font-medium">Your claim is currently being reviewed.</p>
                    @elseif($claim->status === 'field_verification')
                        <p class="text-xs text-purple-600 font-medium">A field inspection has been scheduled.</p>
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
                                @php
                                    $isLast = $loop->last;
                                @endphp
                                <div class="mb-8 relative transition-all duration-300 transform {{ $isLast ? 'scale-105' : '' }}">
                                    <div class="absolute w-4 h-4 {{ $isLast ? 'bg-indigo-600 ring-4 ring-indigo-100' : 'bg-gray-300' }} rounded-full mt-1.5 -left-[1.4rem]"></div>
                                    @if(!$isLast)
                                        <div class="absolute w-0.5 h-full bg-gray-200 left-[-1.02rem] top-4"></div>
                                    @endif
                                    
                                    <div class="flex flex-col ml-2">
                                        <span class="font-bold {{ $isLast ? 'text-indigo-700' : 'text-gray-800' }}">{{ ucwords(str_replace('_', ' ', $log->status)) }}</span>
                                        <div class="flex items-center text-sm mt-1">
                                            <span class="text-gray-500 font-medium">{{ $log->created_at->format('M d, Y - g:i A') }}</span>
                                            <span class="mx-2 text-gray-300">•</span>
                                            <span class="text-indigo-400 text-xs">{{ $log->created_at->diffForHumans() }}</span>
                                        </div>
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
