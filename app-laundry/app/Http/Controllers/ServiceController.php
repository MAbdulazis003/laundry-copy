<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::paginate(20);
        return view('services.index', compact('services'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'pricing_type' => 'required|in:per_kg,per_item',
            'price' => 'required|numeric',
            'estimated_days' => 'nullable|integer',
        ]);

        Service::create($data);
        return redirect()->route('services.index');
    }

    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'pricing_type' => 'required|in:per_kg,per_item',
            'price' => 'required|numeric',
            'estimated_days' => 'nullable|integer',
        ]);

        $service->update($data);
        return redirect()->route('services.index');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.index');
    }
}
