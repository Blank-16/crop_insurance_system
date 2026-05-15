<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Claims Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-alert />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6 border-b pb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Claims on My Plans</h3>
                            <p class="text-sm text-gray-500 mt-1">Review and process farmer claim submissions.</p>
                        </div>
                        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                            {{ $claims->count() }} {{ Str::plural('Claim', $claims->count()) }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b-2 border-gray-200 text-xs uppercase tracking-wider text-gray-500">
                                    <th class="px-4 py-3">Claim</th>
                                    <th class="px-4 py-3">Farmer</th>
                                    <th class="px-4 py-3">Plan / Damage</th>
                                    <th class="px-4 py-3">Est. Payout</th>
                                    <th class="px-4 py-3">Filed</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($claims as $claim)
                                    @php
                                        $rowClass = match(strtolower($claim->status)) {
                                            'approved'         => 'bg-green-50 hover:bg-green-100',
                                            'rejected'         => 'bg-red-50 hover:bg-red-100',
                                            'submitted'        => 'hover:bg-yellow-50 even:bg-gray-50',
                                            'under review'     => 'hover:bg-blue-50 even:bg-gray-50',
                                            'field_verification' => 'hover:bg-purple-50 even:bg-gray-50',
                                            default            => 'hover:bg-gray-50',
                                        };
                                    @endphp
                                    <tr class="{{ $rowClass }} transition duration-150">
                                        <td class="px-4 py-3">
                                            <a href="{{ route('proposer.claims.show', $claim->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold hover:underline">
                                                #{{ $claim->id }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-800">{{ $claim->policy->farmer->name ?? 'Unknown' }}</div>
                                            <div class="text-xs text-gray-500">{{ $claim->policy->farmer->email ?? '' }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-700">{{ $claim->policy->plan->crop_type }} &mdash; {{ $claim->policy->plan->region }}</div>
                                            <div class="text-xs text-red-600 font-medium mt-0.5">
                                                <span class="capitalize">{{ $claim->damage_type }}</span> &bull; {{ $claim->damage_percentage }}% damaged
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 font-bold text-gray-800">
                                            ₹{{ number_format($claim->calculated_amount, 2) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500" title="{{ $claim->created_at->format('M d, Y g:i A') }}">
                                            {{ $claim->created_at->diffForHumans() }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <x-status-badge :status="$claim->status" />
                                            @if($claim->remarks)
                                                <div class="text-xs mt-1 text-red-600 italic max-w-[150px]">{{ $claim->remarks }}</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if(!in_array($claim->status, ['Approved', 'Rejected']))
                                                <form action="{{ route('proposer.claims.update', $claim->id) }}" method="POST"
                                                      class="flex flex-col gap-2 min-w-[180px]"
                                                      onsubmit="return confirmStatusUpdate(this)">
                                                    @csrf
                                                    <select name="status" class="text-sm rounded border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                        @if($claim->status === 'Submitted')
                                                            <option value="Under Review">Move to Under Review</option>
                                                        @elseif($claim->status === 'Under Review')
                                                            <option value="field_verification">Send for Field Verification</option>
                                                            <option value="Approved">Approve Claim</option>
                                                            <option value="Rejected">Reject Claim</option>
                                                        @elseif($claim->status === 'field_verification')
                                                            <option value="Approved">Approve Claim</option>
                                                            <option value="Rejected">Reject Claim</option>
                                                        @endif
                                                    </select>
                                                    <input type="text" name="remarks" placeholder="Remarks (required if rejecting)..."
                                                           class="text-sm rounded border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full" />
                                                    <button type="submit" id="submit-btn-{{ $claim->id }}"
                                                            class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-1.5 px-3 rounded text-xs transition self-start">
                                                        Update Status
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-sm text-gray-400 italic">Finalized</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-14 text-center text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            <p class="text-lg font-medium text-gray-900 mb-1">No claims received yet.</p>
                                            <p class="text-sm text-gray-500">Farmers with active policies on your plans will appear here when they file a claim.</p>
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

    <script>
        function confirmStatusUpdate(form) {
            const select = form.querySelector('select[name="status"]');
            const status = select.options[select.selectedIndex].text;
            const isTerminal = ['Approve Claim', 'Reject Claim'].includes(status);
            if (isTerminal) {
                return confirm(`⚠️ Are you sure you want to "${status}"? This action is final and cannot be undone.`);
            }
            return confirm(`Update this claim to: "${status}"?`);
        }
    </script>
</x-app-layout>
