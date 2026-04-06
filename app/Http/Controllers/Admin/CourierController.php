<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CourierController extends Controller
{
    private function findCourierOrFail(int $id): User
    {
        return User::where('role', 'courier')->findOrFail($id);
    }

    public function index(Request $request)
    {
        $couriers = User::where('role', 'courier')
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")
                      ->orWhere('email', 'like', "%{$request->search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.couriers.index', compact('couriers'));
    }

    public function create()
    {
        return view('admin.couriers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'courier',
        ]);

        return redirect()->route('couriers.index')->with('success', 'Kurir berhasil ditambahkan.');
    }

    public function show(int $id)
    {
        $courier = $this->findCourierOrFail($id);

        $totalAssigned = Order::where('courier_id', $courier->id)->count();
        $completed = Order::where('courier_id', $courier->id)->where('item_status', 'selesai')->count();
        $failed = Order::where('courier_id', $courier->id)->where('item_status', 'gagal')->count();
        $inProgress = Order::where('courier_id', $courier->id)
            ->whereIn('item_status', ['diproses_kurir', 'dikirim', 'sampai'])
            ->count();
        $successRate = $totalAssigned > 0 ? round(($completed / $totalAssigned) * 100) : 0;

        $orders = Order::with(['user', 'items.item'])
            ->where('courier_id', $courier->id)
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('admin.couriers.show', compact(
            'courier',
            'totalAssigned',
            'completed',
            'failed',
            'inProgress',
            'successRate',
            'orders'
        ));
    }

    public function edit(int $id)
    {
        $courier = $this->findCourierOrFail($id);

        return view('admin.couriers.edit', compact('courier'));
    }

    public function update(Request $request, int $id)
    {
        $courier = $this->findCourierOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $courier->id],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $update = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];
        if (!empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        $courier->update($update);

        return redirect()->route('couriers.index')->with('success', 'Data kurir berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $courier = $this->findCourierOrFail($id);

        $hasOrders = Order::where('courier_id', $courier->id)->exists();
        if ($hasOrders) {
            return back()->with('error', 'Kurir tidak bisa dihapus karena sudah memiliki riwayat pengiriman.');
        }

        $courier->delete();

        return back()->with('success', 'Kurir berhasil dihapus.');
    }
}
