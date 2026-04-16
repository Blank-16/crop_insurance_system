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
                                    <tr class="hover:bg-gray-50 border-b">
                                        <td class="px-4 py-2">
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
                                            ${{ number_format($claim->calculated_amount, 2) }}
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
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $claim->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                            <p class="mb-2">You haven't filed any claims yet.</p>
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
