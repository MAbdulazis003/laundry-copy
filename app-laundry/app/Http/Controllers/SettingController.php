<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::first() ?? new Setting([
            'business_name' => 'Laundry Newci',
            'default_due_days' => 1,
            'low_stock_notification' => true,
            'transaction_notification' => true,
        ]);

        return view('settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'business_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:500',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
            'default_due_days' => 'required|integer|min:0|max:30',
            'low_stock_notification' => 'nullable|boolean',
            'transaction_notification' => 'nullable|boolean',
        ]);

        $data['low_stock_notification'] = $request->boolean('low_stock_notification');
        $data['transaction_notification'] = $request->boolean('transaction_notification');
        Setting::updateOrCreate(['id' => 1], $data);

        return redirect()->route('settings.index')->with('status', 'Pengaturan berhasil disimpan.');
    }
}
