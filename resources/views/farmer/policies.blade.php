<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Policies') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-alert />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6 border-b pb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Your Insurance Policies</h3>
                            <p class="text-sm text-gray-500 mt-1">Download your policy document for any active coverage.</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b-2 border-gray-200 text-xs uppercase tracking-wider text-gray-500">
                                    <th class="px-4 py-3">Policy ID</th>
                                    <th class="px-4 py-3">Crop / Region</th>
                                    <th class="px-4 py-3">Provider</th>
                                    <th class="px-4 py-3">Coverage Period</th>
                                    <th class="px-4 py-3">Coverage Amount</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Applied</th>
                                    <th class="px-4 py-3">Document</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($policies as $policy)
                                    @php
                                        $isExpired = $policy->status === 'active' && $policy->end_date && now()->startOfDay()->greaterThan(\Carbon\Carbon::parse($policy->end_date));
                                        $rowClass = match(true) {
                                            $policy->status === 'active' && !$isExpired => 'hover:bg-green-50 even:bg-gray-50',
                                            $isExpired || $policy->status === 'expired'  => 'bg-gray-50 opacity-75',
                                            $policy->status === 'rejected'               => 'bg-red-50 hover:bg-red-100',
                                            default                                      => 'hover:bg-yellow-50 even:bg-gray-50',
                                        };
                                    @endphp
                                    <tr class="{{ $rowClass }} transition duration-150">
                                        <td class="px-4 py-3 font-mono text-sm font-semibold text-gray-700">#{{ $policy->id }}</td>
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-indigo-600">{{ $policy->plan->crop_type ?? 'Unknown' }}</div>
                                            <div class="text-xs text-gray-500">{{ $policy->plan->region ?? '' }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            {{ $policy->plan->proposer->proposerProfile->company_name ?? ($policy->plan->proposer->name ?? 'Unknown') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            @if($policy->start_date && $policy->end_date)
                                                <div class="whitespace-nowrap">{{ \Carbon\Carbon::parse($policy->start_date)->format('M d, Y') }}</div>
                                                <div class="whitespace-nowrap text-xs text-gray-400">to {{ \Carbon\Carbon::parse($policy->end_date)->format('M d, Y') }}</div>
                                            @else
                                                <span class="text-gray-400 italic text-xs">Pending Activation</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 font-bold text-green-700">
                                            ₹{{ number_format($policy->plan->coverage ?? 0, 2) }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($isExpired)
                                                <span class="px-3 py-1 inline-flex items-center gap-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600 border border-gray-300">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Expired
                                                </span>
                                            @elseif($policy->status === 'pending')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-300">
                                                    Pending Review
                                                </span>
                                            @else
                                                <x-status-badge :status="$policy->status" />
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap" title="{{ $policy->created_at->format('M d, Y g:i A') }}">
                                            {{ $policy->created_at->diffForHumans() }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($policy->status === 'active' && !$isExpired)
                                                <a href="{{ route('farmer.policies.pdf', $policy->id) }}"
                                                   target="_blank"
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition duration-150 hover:scale-105 active:scale-95">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                    Download PDF
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-400 italic">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-14 text-center text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="mb-2 text-lg font-medium text-gray-900">No policies found.</p>
                                            <p class="mb-4 text-sm text-gray-500">You haven't applied for any policies yet, or your past policies have expired.</p>
                                            <a href="{{ route('farmer.plans') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition">
                                                Browse Available Plans
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $policies->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

