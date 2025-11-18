@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Policy Overview</h1>
        <a href="{{ route('policies.index') }}" class="text-blue-600 hover:underline">Back to list</a>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div class="bg-white shadow rounded-lg p-5">
            <h2 class="text-lg font-semibold mb-4">Client & Policy</h2>
            <dl class="space-y-2 text-sm">
                <div>
                    <dt class="font-medium text-gray-500">Client</dt>
                    <dd class="text-gray-900">{{ optional($policy->client)->client_name ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Policy Number</dt>
                    <dd class="text-gray-900">{{ $policy->policy_no }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Status</dt>
                    <dd class="text-gray-900">{{ $policy->policy_status_id ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Renewable</dt>
                    <dd class="text-gray-900">{{ $policy->renewable ? 'Yes' : 'No' }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white shadow rounded-lg p-5">
            <h2 class="text-lg font-semibold mb-4">Coverage</h2>
            <dl class="space-y-2 text-sm">
                <div>
                    <dt class="font-medium text-gray-500">Coverage Period</dt>
                    <dd class="text-gray-900">
                        {{ optional($coverage['start_date'])->toFormattedDateString() ?? 'N/A' }} –
                        {{ optional($coverage['end_date'])->toFormattedDateString() ?? 'N/A' }}
                    </dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Sum Insured</dt>
                    <dd class="text-gray-900">{{ number_format($coverage['sum_insured'] ?? 0, 2) }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Base Premium</dt>
                    <dd class="text-gray-900">{{ number_format($coverage['base_premium'] ?? 0, 2) }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-500">Total Premium</dt>
                    <dd class="text-gray-900">{{ number_format($coverage['premium'] ?? 0, 2) }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="mt-8 bg-white shadow rounded-lg">
        <div class="px-5 py-4 border-b">
            <h2 class="text-lg font-semibold">Payment History</h2>
            <p class="text-sm text-gray-500">Instalments and recorded payments for this policy.</p>
        </div>
        <div class="p-5 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead>
                    <tr class="text-left text-gray-500 uppercase tracking-wider">
                        <th class="py-2">Schedule</th>
                        <th class="py-2">Installment</th>
                        <th class="py-2">Due Date</th>
                        <th class="py-2">Amount</th>
                        <th class="py-2">Status</th>
                        <th class="py-2">Payments</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($paymentHistory as $entry)
                        <tr>
                            <td class="py-3">{{ $entry['schedule_no'] ?? '—' }}</td>
                            <td class="py-3">{{ $entry['installment_label'] ?? '—' }}</td>
                            <td class="py-3">{{ $entry['due_date'] ?? '—' }}</td>
                            <td class="py-3">{{ number_format($entry['amount'] ?? 0, 2) }}</td>
                            <td class="py-3">
                                <span class="inline-flex px-2 py-1 rounded text-xs
                                    {{ $entry['status'] === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($entry['status'] ?? 'pending') }}
                                </span>
                            </td>
                            <td class="py-3">
                                <ul class="space-y-1">
                                    @forelse($entry['payments'] as $payment)
                                        <li class="text-xs text-gray-700">
                                            {{ $payment['paid_on'] ?? 'N/A' }} —
                                            {{ number_format($payment['amount'] ?? 0, 2) }}
                                            ({{ $payment['payment_reference'] ?? 'Ref ?' }})
                                        </li>
                                    @empty
                                        <span class="text-gray-400 text-xs">No payments yet</span>
                                    @endforelse
                                </ul>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-500">
                                No payment data available for this policy.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

