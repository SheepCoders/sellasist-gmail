<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SellasistController extends Controller
{
    public function getOrders(Request $request)
    {
        $email = $request->query('email');

        if (!$email) {
            return response()->json(['error' => 'Email is required'], 400);
        }

        $apiKey = env('SELLASIST_API_KEY');
        $baseUrl = 'https://wspolceznatura.sellasist.pl/api/v1/orders';

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'apiKey' => $apiKey,
        ])->get($baseUrl, [
            'email' => $email,
            'date_from' => '2020-11-20 09:08:13',
            'date_to' => now()->format('Y-m-d H:i:s'),
        ]);

        if (!$response->ok()) {
            return response()->json(['error' => 'Failed to fetch data'], 500);
        }

        $orders = collect($response->json())
            ->map(function ($order) {
                return [
                    'order_id' => $order['id'] ?? null,
                    'status' => $order['status']['name'] ?? null,
                    'tracking_number' => $order['tracking_number'] ?? null,
                    'link' => 'https://wspolceznatura.sellasist.pl/admin/orders/edit/' . ($order['id'] ?? '0'),
                ];
            });

        return response()->json($orders);
    }
}
