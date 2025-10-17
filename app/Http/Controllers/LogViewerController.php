<?php
// filepath: c:\Users\acxel\Desktop\Desarrollo\Git Repos\POA\app\Http\Controllers\LogViewerController.php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogViewerController extends Controller
{
    /**
     * Muestra la interfaz principal del visor de logs
     */
    public function index(Request $request)
    {
        $query = ActivityLog::query()->orderByDesc('created_at');

        // Aplicar filtros si se proporcionan
        if ($request->filled('module')) {
            $query->inModule($request->module);
        }

        if ($request->filled('action')) {
            $query->withAction($request->action);
        }

        if ($request->filled('level')) {
            $query->ofLevel($request->level);
        }

        if ($request->filled('user_id')) {
            $query->byUser($request->user_id);
        }

        if ($request->filled('date_start')) {
            $endDate = $request->filled('date_end') ? $request->date_end : null;
            $query->inDateRange($request->date_start, $endDate);
        }

        // Buscar por texto en la descripción
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%");
            });
        }

        // Obtener logs paginados
        $logs = $query->paginate(15)->withQueryString();

        // Datos para filtros
        $modules = ActivityLog::select('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');

        $actions = ActivityLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $levels = ActivityLog::select('level')
            ->distinct()
            ->orderBy('level')
            ->pluck('level');

        $users = User::whereIn('id', ActivityLog::select('user_id')
            ->distinct()
            ->whereNotNull('user_id')
            ->pluck('user_id'))
            ->orderBy('name')
            ->get(['id', 'name']);

        // Estadísticas rápidas
        $stats = [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::whereDate('created_at', today())->count(),
            'errors' => ActivityLog::where('level', 'error')->count(),
            'users_active' => ActivityLog::whereNotNull('user_id')
                 ->distinct('user_id')
                 ->count('user_id')
        ];

        return view('livewire.logs.index', compact(
            'logs',
            'modules',
            'actions',
            'levels',
            'users',
            'stats'
        ));
    }

    /**
     * Muestra los detalles de un log específico
     */
    public function show(ActivityLog $log)
    {
        return view('livewire.logs.detalleLog', compact('log'));
    }

    /**
     * Muestra el dashboard de análisis de logs
     */
    public function dashboard()
    {
        $thirtyDaysAgo = now()->subDays(30);

        // Estadísticas generales
        $totalActivities = ActivityLog::whereDate('created_at', '>=', $thirtyDaysAgo)->count();
        $totalErrors = ActivityLog::where('level', 'error')
            ->whereDate('created_at', '>=', $thirtyDaysAgo)
            ->count();
        $activeUsersCount = ActivityLog::whereDate('created_at', '>=', $thirtyDaysAgo)
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count();
        $moduleCount = ActivityLog::distinct('module')->count();

        // Datos para gráficos
        $dailyActivity = ActivityLog::selectRaw('DATE(created_at) as x, COUNT(*) as y')
            ->whereDate('created_at', '>=', $thirtyDaysAgo)
            ->groupBy('x')
            ->orderBy('x')
            ->get()
            ->map(function ($item) {
                return [
                    'x' => $item->x,
                    'y' => (int)$item->y
                ];
            });

        $moduleActivity = ActivityLog::selectRaw('module, COUNT(*) as total')
            ->groupBy('module')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        $moduleLabels = $moduleActivity->pluck('module');
        $moduleSeries = $moduleActivity->pluck('total');

        $activeUsers = ActivityLog::selectRaw('user_name, COUNT(*) as total')
            ->whereNotNull('user_id')
            ->groupBy('user_name')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        $userLabels = $activeUsers->pluck('user_name');
        $userSeries = $activeUsers->pluck('total');

        $dailyErrors = ActivityLog::selectRaw('DATE(created_at) as x, COUNT(*) as y')
            ->where('level', 'error')
            ->whereDate('created_at', '>=', $thirtyDaysAgo)
            ->groupBy('x')
            ->orderBy('x')
            ->get()
            ->map(function ($item) {
                return [
                    'x' => $item->x,
                    'y' => (int)$item->y
                ];
            });

        return view('livewire.logs.dashboardLog', compact(
            'totalActivities',
            'totalErrors',
            'activeUsersCount',
            'moduleCount',
            'dailyActivity',
            'moduleLabels',
            'moduleSeries',
            'userLabels',
            'userSeries',
            'dailyErrors'
        ));
    }

    /**
     * Elimina logs antiguos
     */
    public function cleanup(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1',
        ]);

        $cutoffDate = now()->subDays($request->days);
        $count = ActivityLog::whereDate('created_at', '<', $cutoffDate)->delete();

        return back()->with('success', "Se eliminaron $count registros de logs antiguos.");
    }
}