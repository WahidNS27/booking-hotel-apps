<?php

namespace App\Http\Controllers;

use App\Models\Login;
use App\Models\Reservation;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('index');
    }

    public function store(Request $request)
    {
        $user = Login::where('email', $request->email)->first();

        if($user && Hash::check($request->password, $user->password)){
            
            Session::put('user', $user->name);

            return redirect('/dashboard');
        }

        return back()->with('error','Email atau Password salah');
    }

    public function getRealtimeStats()
    {
        if(!Session::has('user')){
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $today = Carbon::today();
        
        $data = [
            'todayCheckIns' => Reservation::whereDate('arrival_date', $today)
                ->whereNotIn('status', ['cancelled'])
                ->count(),
            
            'todayCheckOuts' => Reservation::whereDate('departure_date', $today)
                ->whereNotIn('status', ['cancelled', 'checked-out'])
                ->count(),
            
            'todayGuests' => Reservation::whereDate('arrival_date', '<=', $today)
                ->whereDate('departure_date', '>', $today)
                ->whereNotIn('status', ['cancelled', 'checked-out'])
                ->sum('number_of_persons'),
            
            'occupiedRooms' => Reservation::whereDate('arrival_date', '<=', $today)
                ->whereDate('departure_date', '>', $today)
                ->whereNotIn('status', ['cancelled', 'checked-out'])
                ->count(),
            
            'totalReservations' => Reservation::count(),
            'totalGuests' => Guest::count(),
            
            // Data untuk upcoming reservations
            'upcomingCount' => Reservation::with('guest')
                ->whereDate('arrival_date', '>=', $today)
                ->whereDate('arrival_date', '<=', Carbon::today()->addDays(7))
                ->whereNotIn('status', ['cancelled', 'checked-out'])
                ->count(),
            
            // Data untuk recent activities
            'recentActivities' => Reservation::with('guest')
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($reservation) {
                    $guestName = $reservation->guest->name ?? 'Unknown';
                    
                    if ($reservation->status == 'checked-in') {
                        return [
                            'icon' => 'fas fa-sign-in-alt',
                            'iconBg' => 'bg-green-100',
                            'iconColor' => 'text-green-600',
                            'title' => "Check-in: {$guestName} - {$reservation->booking_no}",
                            'time' => $reservation->updated_at->diffForHumans(),
                        ];
                    } elseif ($reservation->status == 'checked-out') {
                        return [
                            'icon' => 'fas fa-sign-out-alt',
                            'iconBg' => 'bg-orange-100',
                            'iconColor' => 'text-orange-600',
                            'title' => "Check-out: {$guestName} - {$reservation->booking_no}",
                            'time' => $reservation->updated_at->diffForHumans(),
                        ];
                    } elseif ($reservation->status == 'cancelled') {
                        return [
                            'icon' => 'fas fa-times-circle',
                            'iconBg' => 'bg-red-100',
                            'iconColor' => 'text-red-600',
                            'title' => "Dibatalkan: {$guestName} - {$reservation->booking_no}",
                            'time' => $reservation->updated_at->diffForHumans(),
                        ];
                    } else {
                        return [
                            'icon' => 'fas fa-calendar-plus',
                            'iconBg' => 'bg-blue-100',
                            'iconColor' => 'text-blue-600',
                            'title' => "Reservasi baru: {$guestName} - {$reservation->booking_no}",
                            'time' => $reservation->updated_at->diffForHumans(),
                        ];
                    }
                }),
        ];
        
        return response()->json($data);
    }

    public function dashboard()
    {
        if(!Session::has('user')){
            return redirect('/');
        }

        $today = Carbon::today();
        
        // Statistik Reservasi Hari Ini
        $todayCheckIns = Reservation::whereDate('arrival_date', $today)
            ->whereNotIn('status', ['cancelled'])
            ->count();
        
        $todayCheckOuts = Reservation::whereDate('departure_date', $today)
            ->whereNotIn('status', ['cancelled', 'checked-out'])
            ->count();
        
        // Total tamu menginap hari ini
        $todayGuests = Reservation::whereDate('arrival_date', '<=', $today)
            ->whereDate('departure_date', '>', $today)
            ->whereNotIn('status', ['cancelled', 'checked-out'])
            ->sum('number_of_persons');
        
        // Total kamar terisi
        $occupiedRooms = Reservation::whereDate('arrival_date', '<=', $today)
            ->whereDate('departure_date', '>', $today)
            ->whereNotIn('status', ['cancelled', 'checked-out'])
            ->count();
        
        // Total reservasi keseluruhan
        $totalReservations = Reservation::count();
        
        // Total guests
        $totalGuests = Guest::count();
        
        // Pendapatan bulan ini (dari room_rate_net)
        $monthlyRevenue = Reservation::whereMonth('arrival_date', $today->month)
            ->whereYear('arrival_date', $today->year)
            ->whereNotIn('status', ['cancelled'])
            ->sum('room_rate_net');
        
        // Pendapatan bulan lalu
        $lastMonthRevenue = Reservation::whereMonth('arrival_date', $today->copy()->subMonth()->month)
            ->whereYear('arrival_date', $today->copy()->subMonth()->year)
            ->whereNotIn('status', ['cancelled'])
            ->sum('room_rate_net');
        
        // Hitung persentase perubahan pendapatan
        $revenueGrowth = $lastMonthRevenue > 0 
            ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;
        
        // Statistik berdasarkan status
        $statusStats = Reservation::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();
        
        // Data untuk chart (7 hari terakhir)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d/m');
            $chartData[] = Reservation::whereDate('arrival_date', $date)->count();
        }
        
        // Reservasi yang akan datang (7 hari ke depan)
       // Reservasi yang akan datang (7 hari ke depan)
            $upcomingReservations = Reservation::with('guest')
            ->whereDate('arrival_date', '>=', Carbon::today())
            ->whereDate('arrival_date', '<=', Carbon::today()->addDays(7))
            ->whereNotIn('status', ['cancelled', 'checked-out']) // Hanya exclude cancelled dan checked-out
            ->orderBy('arrival_date')
            ->get();
        
        // Check-in hari ini list
        $todayCheckInsList = Reservation::with('guest')
            ->whereDate('arrival_date', $today)
            ->whereNotIn('status', ['cancelled'])
            ->orderBy('arrival_time')
            ->limit(5)
            ->get();
        
        // Check-out hari ini list
        $todayCheckOutsList = Reservation::with('guest')
            ->whereDate('departure_date', $today)
            ->whereNotIn('status', ['cancelled', 'checked-out'])
            ->orderBy('departure_date')
            ->limit(5)
            ->get();
        
        // Aktivitas terkini (berdasarkan updated_at)
        $recentActivities = Reservation::with('guest')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($reservation) {
                $guestName = $reservation->guest->name ?? 'Unknown';
                
                if ($reservation->status == 'checked-in') {
                    return [
                        'icon' => 'fas fa-sign-in-alt',
                        'iconBg' => 'bg-green-100',
                        'iconColor' => 'text-green-600',
                        'title' => "Check-in: {$guestName} - {$reservation->booking_no}",
                        'time' => $reservation->updated_at->diffForHumans(),
                    ];
                } elseif ($reservation->status == 'checked-out') {
                    return [
                        'icon' => 'fas fa-sign-out-alt',
                        'iconBg' => 'bg-orange-100',
                        'iconColor' => 'text-orange-600',
                        'title' => "Check-out: {$guestName} - {$reservation->booking_no}",
                        'time' => $reservation->updated_at->diffForHumans(),
                    ];
                } elseif ($reservation->status == 'cancelled') {
                    return [
                        'icon' => 'fas fa-times-circle',
                        'iconBg' => 'bg-red-100',
                        'iconColor' => 'text-red-600',
                        'title' => "Dibatalkan: {$guestName} - {$reservation->booking_no}",
                        'time' => $reservation->updated_at->diffForHumans(),
                    ];
                } else {
                    return [
                        'icon' => 'fas fa-calendar-plus',
                        'iconBg' => 'bg-blue-100',
                        'iconColor' => 'text-blue-600',
                        'title' => "Reservasi baru: {$guestName} - {$reservation->booking_no}",
                        'time' => $reservation->updated_at->diffForHumans(),
                    ];
                }
            });

        return view('dashboard', compact(
            'todayCheckIns',
            'todayCheckOuts',
            'todayGuests',
            'occupiedRooms',
            'totalReservations',
            'totalGuests',
            'monthlyRevenue',
            'revenueGrowth',
            'statusStats',
            'chartLabels',
            'chartData',
            'upcomingReservations',
            'todayCheckInsList',
            'todayCheckOutsList',
            'recentActivities'
        ));
    }

    public function logout()
    {
        Session::forget('user');
        return redirect('/');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Login $login)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Login $login)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Login $login)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Login $login)
    {
        //
    }
}