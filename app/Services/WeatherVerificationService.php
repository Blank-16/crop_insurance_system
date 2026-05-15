<?php

namespace App\Services;

use App\Models\Claim;

class WeatherVerificationService
{
    /**
     * Mocks a verification using an external weather API based on coordinates/region and claim type.
     *
     * @param Claim $claim
     * @return array ['flag' => 'high_probability'|'suspicious', 'reason' => '...']
     */
    public function verifyClaim(Claim $claim): array
    {
        // In a real application, we would use $claim->policy->farmer->farmerProfile->latitude
        // and longitude to query an API like OpenWeatherMap historical data.
        
        $damageType = strtolower($claim->damage_type);
        
        // Mocking logic
        if ($damageType === 'drought') {
            // Simulated: Checked last 30 days rainfall
            return [
                'flag' => 'high_probability',
                'reason' => 'External Weather API confirms less than 10mm rainfall in the reported region over the last 30 days.'
            ];
        }

        if ($damageType === 'flood') {
            // Simulated: Checked last 7 days rainfall
            return [
                'flag' => 'high_probability',
                'reason' => 'External Weather API confirms excessive rainfall (>150mm) in the reported region.'
            ];
        }

        if ($damageType === 'pest') {
            // Simulated: Unverifiable by weather
            return [
                'flag' => 'suspicious',
                'reason' => 'Damage type (Pest) cannot be verified by weather data. Field audit highly recommended.'
            ];
        }

        // Default
        return [
            'flag' => 'suspicious',
            'reason' => 'Weather patterns do not strongly correlate with the reported damage. Manual review needed.'
        ];
    }
}
