<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Claims') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="mb-4 flex justify-end">
                <a href="{{ route('farmer.claims.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                    File New Claim
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Submitted Claims</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 border-b">Claim ID</th>
                                    <th class="px-4 py-2 border-b">Policy Plan</th>
                                    <th class="px-4 py-2 border-b">Damage Info</th>
                                    <th class="px-4 py-2 border-b">Est. Amount</th>
                                    <th class="px-4 py-2 border-b">Document</th>
                                    <th class="px-4 py-2 border-b">Status</th>
                                    <th class="px-4 py-2 border-b">Submitted At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($claims as $claim)
                                    <tr class="hover:bg-indigo-50 even:bg-gray-50 border-b transition duration-150">
                                        <td class="px-4 py-3">
                                            <a href="{{ route('farmer.claims.show', $claim->id) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold hover:underline">
                                                #{{ $claim->id }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-2">
                                            <div>{{ $claim->policy->plan->crop_type ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">Region: {{ $claim->policy->plan->region ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-4 py-2">
                                            <div class="text-sm font-medium capitalize">{{ $claim->damage_type ?? 'N/A' }}</div>
                                            <div class="text-xs text-red-500">{{ $claim->damage_percentage ?? 0 }}% Damage</div>
                                        </td>
                                        <td class="px-4 py-2 font-bold text-gray-800">
                                            ₹{{ number_format($claim->calculated_amount, 2) }}
                                        </td>
                                        <td class="px-4 py-2">
                                            @if($claim->documents->isNotEmpty())
                                                <a href="{{ asset('storage/' . $claim->documents->first()->file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 underline text-sm">View Doc</a>
                                            @else
                                                <span class="text-gray-400 text-sm">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2">
                                            <x-status-badge :status="$claim->status" />
                                            @if($claim->remarks)
                                                <div class="text-xs mt-1 text-red-600 italic break-words max-w-[150px]">{{ $claim->remarks }}</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500" title="{{ $claim->created_at->format('M d, Y g:i A') }}">
                                            {{ $claim->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-12 text-center text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="mb-2 text-lg font-medium text-gray-900">You haven't filed any claims yet.</p>
                                            <p class="text-sm text-gray-500">If your crop has suffered damage, you can file a new claim to request compensation.</p>
                                        </td>
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
