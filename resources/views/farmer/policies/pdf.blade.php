<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Policy Certificate #{{ $policy->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; color: #1a1a2e; background: #fff; font-size: 13px; }

        .page { padding: 48px; max-width: 740px; margin: 0 auto; }

        /* Header */
        .header { border-bottom: 3px solid #4f46e5; padding-bottom: 24px; margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-start; }
        .brand { }
        .brand-name { font-size: 22px; font-weight: 700; color: #4f46e5; letter-spacing: -0.5px; }
        .brand-tagline { font-size: 11px; color: #6b7280; margin-top: 2px; }
        .doc-info { text-align: right; }
        .doc-title { font-size: 18px; font-weight: 700; color: #111827; }
        .doc-subtitle { font-size: 11px; color: #6b7280; margin-top: 3px; }
        .doc-id { font-size: 11px; color: #4f46e5; font-weight: 600; margin-top: 4px; }

        /* Status Banner */
        .status-banner { background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 6px; padding: 10px 16px; margin-bottom: 28px; display: flex; justify-content: space-between; align-items: center; }
        .status-text { font-size: 12px; color: #166534; font-weight: 600; }
        .status-badge { background: #16a34a; color: white; font-size: 11px; font-weight: 700; padding: 3px 12px; border-radius: 20px; }

        /* Section */
        .section { margin-bottom: 28px; }
        .section-title { font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.08em; border-bottom: 1px solid #e5e7eb; padding-bottom: 6px; margin-bottom: 14px; }

        .grid-2 { display: table; width: 100%; }
        .grid-2 .col { display: table-cell; width: 50%; vertical-align: top; padding-right: 24px; }
        .grid-2 .col:last-child { padding-right: 0; }

        .field { margin-bottom: 12px; }
        .field-label { font-size: 10px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 2px; }
        .field-value { font-size: 13px; color: #111827; font-weight: 600; }
        .field-value.accent { color: #4f46e5; }
        .field-value.money { color: #16a34a; font-size: 16px; }
        .field-value.danger { color: #dc2626; }

        /* Coverage Box */
        .coverage-box { background: #eff6ff; border: 1.5px solid #bfdbfe; border-radius: 8px; padding: 20px 24px; display: table; width: 100%; margin-bottom: 28px; }
        .coverage-item { display: table-cell; width: 33%; text-align: center; }
        .coverage-item + .coverage-item { border-left: 1px solid #bfdbfe; }
        .coverage-num { font-size: 22px; font-weight: 700; color: #1d4ed8; }
        .coverage-label { font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 4px; }

        /* Footer */
        .footer { border-top: 1.5px solid #e5e7eb; padding-top: 16px; margin-top: 40px; display: flex; justify-content: space-between; align-items: center; }
        .footer-note { font-size: 10px; color: #9ca3af; max-width: 60%; }
        .footer-stamp { text-align: right; font-size: 10px; color: #6b7280; }
        .footer-stamp strong { color: #4f46e5; }

        /* Watermark for non-active */
        .watermark { position: fixed; top: 40%; left: 10%; font-size: 72px; color: rgba(239,68,68,0.08); font-weight: 900; transform: rotate(-30deg); letter-spacing: 8px; z-index: 0; }
    </style>
</head>
<body>
<div class="page">
    {{-- Header --}}
    <div class="header">
        <div class="brand">
            <div class="brand-name">&#127807; AgriShield</div>
            <div class="brand-tagline">Crop Insurance & Claims Management System</div>
        </div>
        <div class="doc-info">
            <div class="doc-title">Policy Certificate</div>
            <div class="doc-subtitle">Official Coverage Document</div>
            <div class="doc-id">Policy #{{ str_pad($policy->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>
    </div>

    {{-- Status Banner --}}
    <div class="status-banner">
        <span class="status-text">&#x2714; This policy is currently <strong>active and valid</strong>. Coverage is in force.</span>
        <span class="status-badge">ACTIVE</span>
    </div>

    {{-- Section: Policyholder --}}
    <div class="section">
        <div class="section-title">Policyholder Information</div>
        <div class="grid-2">
            <div class="col">
                <div class="field">
                    <div class="field-label">Full Name</div>
                    <div class="field-value">{{ $policy->farmer->name }}</div>
                </div>
                <div class="field">
                    <div class="field-label">Email Address</div>
                    <div class="field-value">{{ $policy->farmer->email }}</div>
                </div>
            </div>
            <div class="col">
                <div class="field">
                    <div class="field-label">Farm Size</div>
                    <div class="field-value">{{ $policy->farmer->farmerProfile->land_size ?? 'N/A' }}</div>
                </div>
                <div class="field">
                    <div class="field-label">Primary Crop</div>
                    <div class="field-value accent">{{ $policy->farmer->farmerProfile->crop_type ?? 'N/A' }}</div>
                </div>
                <div class="field">
                    <div class="field-label">Farm Region</div>
                    <div class="field-value">{{ $policy->farmer->farmerProfile->region ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Coverage Box --}}
    <div class="coverage-box">
        <div class="coverage-item">
            <div class="coverage-num">${{ number_format($policy->plan->coverage, 0) }}</div>
            <div class="coverage-label">Max Coverage</div>
        </div>
        <div class="coverage-item">
            <div class="coverage-num">${{ number_format($policy->plan->premium, 0) }}</div>
            <div class="coverage-label">Annual Premium</div>
        </div>
        <div class="coverage-item">
            <div class="coverage-num">{{ $policy->plan->duration }} mo</div>
            <div class="coverage-label">Policy Duration</div>
        </div>
    </div>

    {{-- Section: Plan Details --}}
    <div class="section">
        <div class="section-title">Insurance Plan Details</div>
        <div class="grid-2">
            <div class="col">
                <div class="field">
                    <div class="field-label">Insured Crop Type</div>
                    <div class="field-value accent">{{ $policy->plan->crop_type }}</div>
                </div>
                <div class="field">
                    <div class="field-label">Coverage Region</div>
                    <div class="field-value">{{ $policy->plan->region }}</div>
                </div>
                <div class="field">
                    <div class="field-label">Insurance Provider</div>
                    <div class="field-value">{{ $policy->plan->proposer->proposerProfile->company_name ?? $policy->plan->proposer->name }}</div>
                </div>
            </div>
            <div class="col">
                <div class="field">
                    <div class="field-label">Policy Start Date</div>
                    <div class="field-value">{{ \Carbon\Carbon::parse($policy->start_date)->format('F d, Y') }}</div>
                </div>
                <div class="field">
                    <div class="field-label">Policy Expiry Date</div>
                    <div class="field-value danger">{{ \Carbon\Carbon::parse($policy->end_date)->format('F d, Y') }}</div>
                </div>
                <div class="field">
                    <div class="field-label">Days Remaining</div>
                    <div class="field-value">{{ max(0, now()->diffInDays(\Carbon\Carbon::parse($policy->end_date), false)) }} days</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-note">
            This document is a system-generated policy certificate. It is valid as proof of insurance coverage.
            For claim submissions, log in to the AgriShield portal.
        </div>
        <div class="footer-stamp">
            Generated on {{ now()->format('M d, Y g:i A') }}<br>
            <strong>AgriShield Crop Insurance System</strong>
        </div>
    </div>
</div>
</body>
</html>
