<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Policy Applications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-alert />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6 border-b pb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Farmer Policy Applications</h3>
                            <p class="text-sm text-gray-500 mt-1">Review and approve or reject pending policy applications.</p>
                        </div>
                        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                            {{ $policies->total() }} {{ Str::plural('Application', $policies->total()) }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b-2 border-gray-200 text-xs uppercase tracking-wider text-gray-500">
                                    <th class="px-4 py-3">Policy ID</th>
                                    <th class="px-4 py-3">Farmer</th>
                                    <th class="px-4 py-3">Crop Plan</th>
                                    <th class="px-4 py-3">Coverage</th>
                                    <th class="px-4 py-3">Applied</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($policies as $policy)
                                    @php
                                        $rowClass = match($policy->status) {
                                            'active'   => 'bg-green-50 hover:bg-green-100',
                                            'rejected' => 'bg-red-50 hover:bg-red-100',
                                            default    => 'hover:bg-yellow-50 even:bg-gray-50',
                                        };
                                    @endphp
                                    <tr class="{{ $rowClass }} transition duration-150">
                                        <td class="px-4 py-3 font-mono text-sm">#{{ $policy->id }}</td>
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-800">{{ $policy->farmer->name ?? 'Unknown Farmer' }}</div>
                                            <div class="text-xs text-gray-500">{{ $policy->farmer->email ?? '' }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-700">{{ $policy->plan->crop_type ?? 'Unknown' }}</div>
                                            <div class="text-xs text-gray-500">Region: {{ $policy->plan->region ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-4 py-3 font-semibold text-gray-800">
                                            ₹{{ number_format($policy->plan->coverage, 2) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500" title="{{ $policy->created_at->format('M d, Y g:i A') }}">
                                            {{ $policy->created_at->diffForHumans() }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <x-status-badge :status="$policy->status" />
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($policy->status === 'pending')
                                                <div class="flex gap-2">
                                                    <form action="{{ route('proposer.policies.update', $policy->id) }}" method="POST"
                                                          onsubmit="return confirm('Approve this policy for {{ addslashes($policy->farmer->name ?? 'this farmer') }}? This will activate their coverage immediately.')">
                                                        @csrf
                                                        <input type="hidden" name="status" value="active">
                                                        <button type="submit"
                                                                class="bg-green-500 hover:bg-green-600 text-white font-semibold py-1.5 px-3 rounded text-xs transition">
                                                            ✓ Approve
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('proposer.policies.update', $policy->id) }}" method="POST"
                                                          onsubmit="return confirm('Reject this policy application? The farmer will be notified.')">
                                                        @csrf
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit"
                                                                class="bg-red-500 hover:bg-red-600 text-white font-semibold py-1.5 px-3 rounded text-xs transition">
                                                            ✕ Reject
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-gray-400 text-sm italic">Finalized</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-14 text-center text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="text-lg font-medium text-gray-900 mb-1">No applications yet.</p>
                                            <p class="text-sm text-gray-500">Farmers who apply for your insurance plans will appear here for review.</p>
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
