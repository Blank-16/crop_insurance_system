<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Applied Policies') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-alert />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Your Policies</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 border-b">Policy ID</th>
                                    <th class="px-4 py-2 border-b">Crop Plan</th>
                                    <th class="px-4 py-2 border-b">Provider</th>
                                    <th class="px-4 py-2 border-b">Validity (Start - End)</th>
                                    <th class="px-4 py-2 border-b">Status</th>
                                    <th class="px-4 py-2 border-b">Applied On</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($policies as $policy)
                                @forelse($policies as $policy)
                                    <tr class="hover:bg-indigo-50 even:bg-gray-50 border-b transition duration-150">
                                        <td class="px-4 py-3 font-mono">#{{ $policy->id }}</td>
                                        <td class="px-4 py-3 text-indigo-600 font-medium">{{ $policy->plan->crop_type ?? 'Unknown' }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $policy->plan->proposer->name ?? 'Unknown Company' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            @if($policy->status === 'active' || $policy->start_date)
                                                <div class="whitespace-nowrap">{{ \Carbon\Carbon::parse($policy->start_date)->format('M d, Y') }} -</div>
                                                <div class="whitespace-nowrap">{{ \Carbon\Carbon::parse($policy->end_date)->format('M d, Y') }}</div>
                                            @else
                                                <span class="text-gray-400 italic">Coverage Not Active</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($policy->status === 'active' && $policy->end_date && now()->startOfDay()->greaterThan(\Carbon\Carbon::parse($policy->end_date)))
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 shadow-sm border border-red-200">Expired</span>
                                            @elseif($policy->status === 'pending')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 shadow-sm border border-yellow-200" title="Your request is currently being reviewed.">Pending Review</span>
                                            @else
                                                <x-status-badge :status="$policy->status" />
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500" title="{{ $policy->created_at->format('M d, Y g:i A') }}">
                                            {{ $policy->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="mb-2 text-lg font-medium text-gray-900">No active policies found.</p>
                                            <p class="mb-4 text-sm text-gray-500">You haven't applied for any policies yet, or your past policies have expired.</p>
                                            <a href="{{ route('farmer.plans') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Browse Available Plans</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                {{ $policies->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
