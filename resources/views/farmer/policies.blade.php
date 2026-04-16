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
                                    <tr class="hover:bg-gray-50 border-b">
                                        <td class="px-4 py-2 font-mono">#{{ $policy->id }}</td>
                                        <td class="px-4 py-2 text-indigo-600 font-medium">{{ $policy->plan->crop_type ?? 'Unknown' }}</td>
                                        <td class="px-4 py-2 text-gray-600">{{ $policy->plan->proposer->name ?? 'Unknown Company' }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-600">
                                            @if($policy->status === 'active' || $policy->start_date)
                                                <div class="whitespace-nowrap">{{ \Carbon\Carbon::parse($policy->start_date)->format('M d, Y') }} -</div>
                                                <div class="whitespace-nowrap">{{ \Carbon\Carbon::parse($policy->end_date)->format('M d, Y') }}</div>
                                            @else
                                                <span class="text-gray-400 italic">Not Active</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2">
                                            @if($policy->status === 'active' && $policy->end_date && now()->startOfDay()->greaterThan(\Carbon\Carbon::parse($policy->end_date)))
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Expired</span>
                                            @else
                                                <x-status-badge :status="$policy->status" />
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $policy->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                            <p class="mb-2">You haven't applied for any policies yet.</p>
                                            <a href="{{ route('farmer.plans') }}" class="text-indigo-600 hover:text-indigo-900 underline font-medium">Browse Available Plans</a>
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
