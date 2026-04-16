<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proposer Claims Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Claims on My Policies</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 border-b">Claim ID</th>
                                    <th class="px-4 py-2 border-b">Farmer</th>
                                    <th class="px-4 py-2 border-b">Policy/Plan</th>
                                    <th class="px-4 py-2 border-b">Document</th>
                                    <th class="px-4 py-2 border-b">Status</th>
                                    <th class="px-4 py-2 border-b">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($claims as $claim)
                                    <tr class="hover:bg-gray-50 border-b">
                                        <td class="px-4 py-2">
                                            <a href="{{ route('proposer.claims.show', $claim->id) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold hover:underline">
                                                #{{ $claim->id }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-2">{{ $claim->policy->farmer->name ?? 'Unknown' }}</td>
                                        <td class="px-4 py-2">
                                            <div>Plan #{{ $claim->policy->plan->id }}</div>
                                            <div class="text-sm text-gray-500">{{ $claim->policy->plan->crop_type }} ({{ $claim->policy->plan->region }})</div>
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
                                        </td>
                                        <td class="px-4 py-2">
                                            @if(!in_array($claim->status, ['Approved', 'Rejected']))
                                                <form action="{{ route('proposer.claims.update', $claim->id) }}" method="POST" class="flex flex-col gap-2">
                                                    @csrf
                                                    <select name="status" class="text-sm rounded border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                        <option value="Under Review" {{ $claim->status == 'Under Review' ? 'selected' : '' }}>Under Review</option>
                                                        <option value="field_verification" {{ $claim->status == 'field_verification' ? 'selected' : '' }}>Field Verification</option>
                                                        <option value="Approved">Approve</option>
                                                        <option value="Rejected">Reject</option>
                                                    </select>
                                                    <input type="text" name="remarks" placeholder="Optional remarks..." class="text-sm rounded border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full" />
                                                    <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-1 px-2 rounded text-xs transition self-start">Update</button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 text-sm italic">Processed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">No claims submitted by farmers yet.</td>
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
