<?php

namespace App\Services;

use App\Models\Token;
use Illuminate\Support\Facades\DB;

class TokenService
{
    /**
     * Generate and return the next token number by code
     *
     * @param string|null $code
     * @return string
     */
    public function getTokenByCode(string $code): string
    {
        if (!$code) {
            throw new \InvalidArgumentException("Code is required");
        }

        // Start a DB transaction to avoid race conditions
        return \DB::transaction(callback: function () use ($code) {

            $token = DB::table('token')->where('code', $code)->lockForUpdate()->first();

            if (!$token) {
                // If token does not exist, create it with initial 1
                $token = DB::table('token')->create([
                    'code' => $code,
                    'tokenNo' => 1
                ]);
            }

            // Return tokenNo with leading zeros (5 digits)
            return str_pad($token->tokenNo, 5, '0', STR_PAD_LEFT);
        });
    }
}
