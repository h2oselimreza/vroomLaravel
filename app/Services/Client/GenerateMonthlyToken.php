<?php

namespace App\Services\Client;

use App\Models\Token;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GenerateMonthlyToken
{
    public function generateMonthlyToken($code = null)
    {
        if (!$code) {
            return '0001';
        }

        return DB::transaction(function () use ($code) {

            $token = Token::where('code', $code)
                ->lockForUpdate()
                ->first();

            if (!$token) {
                Token::create([
                    'code'     => $code,
                    'dt'       => now()->toDateString(),
                    'tokenNo'  => '0001',
                ]);

                return '0001';
            }

            $expMonth   = Carbon::parse($token->dt)->format('Y-m');
            $todayMonth = now()->format('Y-m');

            if ($todayMonth > $expMonth) {
                $token->update([
                    'dt'      => now()->toDateString(),
                    'tokenNo' => '0001',
                ]);

                return '0001';
            }

            $nextTokenNo = str_pad(((int) $token->tokenNo) + 1, 4, '0', STR_PAD_LEFT);

            $token->update([
                'dt'      => now()->toDateString(),
                'tokenNo' => $nextTokenNo,
            ]);

            return $nextTokenNo;
        });
    }
}