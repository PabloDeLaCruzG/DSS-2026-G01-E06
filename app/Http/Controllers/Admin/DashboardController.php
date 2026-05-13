<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Order;
use App\Models\GameAd;
use App\Models\Report;

class DashboardController extends Controller
{
    public function __construct()
    {
        // proteger con auth; además comprobamos isAdmin en index por seguridad
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // seguridad: permitir solo admins (User::isAdmin() existe en tu modelo)
        if (! auth()->user() || ! auth()->user()->isAdmin()) {
            abort(403);
        }

        // 1) métricas principales
        $totalUsers = User::count();

        // total revenue (suma de total_amount en orders)
        $totalRevenue = (float) Order::sum('total_amount');

        // active listings (GameAd::STATUS_ACTIVE definido en tu modelo)
        $activeListings = GameAd::where('status', GameAd::STATUS_ACTIVE)->count();

        // open reports
        $openReports = Report::where('status', Report::STATUS_OPEN)->count();

        // 2) flagged content (últimos 5 reportes abiertos con relaciones)
        $flagged = Report::with(['gameAd', 'user'])
            ->where('status', Report::STATUS_OPEN)
            ->latest()
            ->take(5)
            ->get();

        // 3) recent activity: combinamos orders, reports y nuevos usuarios en una sola colección ordenada
        $recentOrders = Order::with('user')->latest()->take(6)->get();
        $recentReports = Report::with('user')->latest()->take(6)->get();
        $recentUsers = User::latest()->take(6)->get();

        $activities = collect();

        foreach ($recentOrders as $o) {
            $activities->push([
                'type' => 'order',
                'user' => $o->user?->name ?? 'Usuario',
                'action' => 'Order Completed',
                'details' => 'Pedido #' . $o->id,
                'created_at' => $o->created_at,
            ]);
        }

        foreach ($recentReports as $r) {
            $activities->push([
                'type' => 'report',
                'user' => $r->user?->name ?? 'Usuario',
                'action' => 'Report',
                'details' => $r->reason ?? ($r->gameAd?->game->title ?? 'Contenido reportado'),
                'created_at' => $r->created_at,
            ]);
        }

        foreach ($recentUsers as $u) {
            $activities->push([
                'type' => 'user',
                'user' => $u->name,
                'action' => 'New Registration',
                'details' => $u->email,
                'created_at' => $u->created_at,
            ]);
        }

        $activities = $activities->sortByDesc('created_at')->values()->take(10);

        // 4) datos para el chart: revenue por día últimos 30 días
        $end = Carbon::now()->endOfDay();
        $start = Carbon::now()->subDays(29)->startOfDay(); // 30 días: 0..29

        $ordersByDay = Order::whereBetween('created_at', [$start, $end])
            ->selectRaw("DATE(created_at) as date, SUM(total_amount) as total")
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date')
            ->map(fn($r) => (float) $r->total);

        // preparar arrays completos (incluir días sin datos)
        $labels = [];
        $data = [];
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $key = $d->toDateString();
            $labels[] = $d->format('d M'); // ej. "05 May"
            $data[] = $ordersByDay->has($key) ? $ordersByDay->get($key) : 0;
        }

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalRevenue' => $totalRevenue,
            'activeListings' => $activeListings,
            'openReports' => $openReports,
            'flagged' => $flagged,
            'activities' => $activities,
            'chartLabels' => $labels,
            'chartData' => $data,
        ]);
    }
}