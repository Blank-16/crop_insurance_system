<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Farmer Policies') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-alert />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Policy Applications</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 border-b">Policy ID</th>
                                    <th class="px-4 py-2 border-b">Farmer</th>
                                    <th class="px-4 py-2 border-b">Crop Plan</th>
                                    <th class="px-4 py-2 border-b">Status</th>
                                    <th class="px-4 py-2 border-b">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($policies as $policy)
                                    <tr class="hover:bg-gray-50 border-b">
                                        <td class="px-4 py-2 font-mono">#{{ $policy->id }}</td>
                                        <td class="px-4 py-2 text-indigo-600 font-medium">{{ $policy->farmer->name ?? 'Unknown Farmer' }}</td>
                                        <td class="px-4 py-2">Plan #{{ $policy->plan->id }} - {{ $policy->plan->crop_type ?? 'Unknown' }}</td>
                                        <td class="px-4 py-2">
                                            <x-status-badge :status="$policy->status" />
                                        </td>
                                        <td class="px-4 py-2">
                                            @if($policy->status === 'pending')
                                                <form action="{{ route('proposer.policies.update', $policy->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <input type="hidden" name="status" value="active">
                                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-3 rounded text-xs transition">Approve</button>
                                                </form>
                                                <form action="{{ route('proposer.policies.update', $policy->id) }}" method="POST" class="inline-block ml-1">
                                                    @csrf
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-xs transition">Reject</button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 text-sm italic">Resolved</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                            <p class="mb-2">There are no policy applications for your plans.</p>
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
