<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; 

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::with('guest')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('reservations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // CEK USER LOGIN MANUAL - menggunakan session
    if (!session()->has('user')) {
        Log::error('User tidak login');
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
    }
    
    $userName = session('user');
    
    try {
        // Validasi data - termasuk credit card fields
        $validated = $request->validate([
            // Data Tamu
            'guest_name' => 'required|string|max:255',
            'guest_phone' => 'required|string|max:20',
            'guest_email' => 'nullable|email|max:255',
            'guest_company' => 'nullable|string|max:255',
            
            // Data Kamar
            'room_numbers' => 'required|array',
            'room_numbers.*' => 'string|max:10',
            'number_of_rooms' => 'required|integer|min:1',
            'number_of_persons' => 'required|integer|min:1',
            'room_type' => 'required|string|max:100',
            'arrival_date' => 'required|date',
            'arrival_time' => 'nullable',
            'departure_date' => 'required|date|after:arrival_date',
            
            // Data Agent
            'company_agent' => 'nullable|string|max:255',
            'agent_telp' => 'nullable|string|max:20',
            'agent_fax' => 'nullable|string|max:20',
            'agent_email' => 'nullable|email|max:255',
            
            // Data Harga & Pembayaran
            'room_rate_net' => 'nullable|integer',
            'payment_method' => 'nullable|in:Bank Transfer,Credit Card,Cash',
            'status' => 'nullable|in:guaranteed,non-guaranteed,cancelled,checked-in,checked-out',
            
            // Data Bank Transfer
            'bank_account' => 'nullable|string|max:255',
            'bank_account_name' => 'nullable|string|max:255',
            
            // DATA CREDIT CARD - PASTIKAN INI ADA
            'cc_number' => 'nullable|string|max:20',
            'cc_holder_name' => 'nullable|string|max:255',
            'cc_type' => 'nullable|string|max:50',
            'cc_expired' => 'nullable|string|max:10',
            'cc_signature' => 'nullable|string',
            
            // Data Tambahan
            'safety_deposit_box' => 'nullable|string|max:50',
            'issued_by' => 'nullable|string|max:255',
            'issued_date' => 'nullable|date',
            'book_by' => 'nullable|string|max:255',
            'cancellation_number' => 'nullable|string|max:255',
        ]);
        
        // Hitung total nights dari tanggal
        $arrival = Carbon::parse($validated['arrival_date']);
        $departure = Carbon::parse($validated['departure_date']);
        $totalNights = $arrival->diffInDays($departure);
        
        // Proses Guest - buat guest baru
        $guest = Guest::create([
            'name' => $validated['guest_name'],
            'phone' => $validated['guest_phone'],
            'email' => $validated['guest_email'] ?? null,
            'company' => $validated['guest_company'] ?? null,
        ]);
        
        // Generate booking number
        $bookingNo = $this->generateBookingNumber();
        
        // Untuk user_id, gunakan default 1
        $userId = 1;
        
        // Siapkan data untuk semua kolom
        $reservationData = [
            // Foreign Keys
            'guest_id' => $guest->id,
            'user_id' => $userId,
            
            // Informasi Booking
            'booking_no' => $bookingNo,
            'book_by' => $validated['book_by'] ?? $userName,
            'booking_date' => now()->format('Y-m-d'),
            
            // Data Kamar
            'room_numbers' => json_encode($validated['room_numbers']),
            'number_of_rooms' => $validated['number_of_rooms'],
            'number_of_persons' => $validated['number_of_persons'],
            'room_type' => $validated['room_type'],
            'arrival_date' => $validated['arrival_date'],
            'arrival_time' => $validated['arrival_time'] ?? '14:00:00',
            'departure_date' => $validated['departure_date'],
            'total_nights' => $totalNights,
            
            // Data Agent
            'company_agent' => $validated['company_agent'] ?? null,
            'agent_telp' => $validated['agent_telp'] ?? null,
            'agent_fax' => $validated['agent_fax'] ?? null,
            'agent_email' => $validated['agent_email'] ?? null,
            
            // Data Harga
            'room_rate_net' => $validated['room_rate_net'] ?? 0,
            
            // Data Pembayaran
            'payment_method' => $validated['payment_method'] ?? null,
            'bank_account' => $validated['bank_account'] ?? null,
            'bank_account_name' => $validated['bank_account_name'] ?? null,
            
            // DATA CREDIT CARD - PASTIKAN INI DISIMPAN
            'cc_number' => $validated['cc_number'] ?? null,
            'cc_holder_name' => $validated['cc_holder_name'] ?? null,
            'cc_type' => $validated['cc_type'] ?? null,
            'cc_expired' => $validated['cc_expired'] ?? null,
            'cc_signature' => $validated['cc_signature'] ?? null,
            
            // Data Tambahan
            'safety_deposit_box' => $validated['safety_deposit_box'] ?? null,
            'issued_by' => $validated['issued_by'] ?? $userName,
            'issued_date' => $validated['issued_date'] ?? now()->format('Y-m-d'),
            
            // Status
            'status' => $validated['status'] ?? 'non-guaranteed',
            'cancellation_number' => $validated['cancellation_number'] ?? null,
        ];
        
        // Simpan ke database dengan transaksi
        DB::beginTransaction();
        
        try {
            $reservation = Reservation::create($reservationData);
            DB::commit();
            
            return redirect()->route('reservations.show', $reservation->id)
                ->with('success', 'Reservasi berhasil dibuat dengan nomor: ' . $bookingNo);
                
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
            
    } catch (\Illuminate\Validation\ValidationException $e) {
        return back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        Log::error('ERROR: ' . $e->getMessage());
        return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
    }
}

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        $reservation->load('guest');
        $reservation->room_numbers = json_decode($reservation->room_numbers);
        
        return view('reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        $reservation->room_numbers = json_decode($reservation->room_numbers);
        return view('reservations.edit', compact('reservation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            // Data Kamar
            'room_numbers' => 'required|array',
            'room_numbers.*' => 'string|max:10',
            'number_of_rooms' => 'required|integer|min:1',
            'number_of_persons' => 'required|integer|min:1',
            'room_type' => 'required|string|max:100',
            'arrival_date' => 'required|date',
            'arrival_time' => 'nullable',
            'departure_date' => 'required|date|after:arrival_date',
            'room_rate_net' => 'nullable|integer',
            'status' => 'nullable|in:guaranteed,non-guaranteed,cancelled,checked-in,checked-out',
            'payment_method' => 'required|in:cash,credit_card,bank_transfer',
            
            // Bank Transfer
            'bank_account' => 'nullable|string',
            'bank_account_name' => 'nullable|string',
            
            // CREDIT CARD FIELDS
            'cc_number' => 'nullable|string|max:20',
            'cc_holder_name' => 'nullable|string|max:255',
            'cc_type' => 'nullable|string|max:50',
            'cc_expired' => 'nullable|string|max:10',
            'cc_signature' => 'nullable|string',
            
            // Data lainnya
            'company_agent' => 'nullable|string',
            'agent_telp' => 'nullable|string',
            'agent_fax' => 'nullable|string',
            'agent_email' => 'nullable|email',
            'safety_deposit_box' => 'nullable|string',
            'issued_by' => 'nullable|string',
            'issued_date' => 'nullable|date',
            'cancellation_number' => 'nullable|string',
        ]);
    
        // Hitung ulang total nights
        $arrival = Carbon::parse($validated['arrival_date']);
        $departure = Carbon::parse($validated['departure_date']);
        $validated['total_nights'] = $arrival->diffInDays($departure);
        $validated['room_numbers'] = json_encode($validated['room_numbers']);
    
        // Update guest data
        if ($request->has('guest_name') || $request->has('guest_phone')) {
            $reservation->guest->update([
                'name' => $request->guest_name,
                'phone' => $request->guest_phone,
                'email' => $request->guest_email,
                'company' => $request->guest_company,
            ]);
        }
    
        $reservation->update($validated);
    
        return redirect()->route('reservations.show', $reservation->id)
            ->with('success', 'Reservasi berhasil diupdate');
    }
    
    public function generatePdf(Reservation $reservation)
    {
        try {
            // Load relasi guest
            $reservation->load('guest');
            
            // Decode room numbers
            $reservation->room_numbers = json_decode($reservation->room_numbers);
            
            // Debug: lihat data yang ada
            \Log::info('Data Credit Card:', [
                'cc_number' => $reservation->cc_number,
                'cc_holder_name' => $reservation->cc_holder_name,
                'cc_type' => $reservation->cc_type,
                'cc_expired' => $reservation->cc_expired,
                'payment_method' => $reservation->payment_method,
            ]);
            
            // Cek apakah logo ada
            $logoPath = public_path('img/ppkdjp.jpg');
            
            // Data untuk PDF
            $data = [
                'reservation' => $reservation,
                'logo_path' => $logoPath,
                'tanggal_cetak' => now()->format('d/m/Y H:i:s'),
            ];
            
            $pdf = Pdf::loadView('reservations.pdf', $data);
            $pdf->setPaper('A4', 'portrait');
            
            $safeBookingNumber = str_replace('/', '-', $reservation->booking_no);
            $fileName = 'Reservation-Confirmation-' . $safeBookingNumber . '.pdf';
            
            return $pdf->stream($fileName);
            
        } catch (\Exception $e) {
            \Log::error('Error generating PDF: ' . $e->getMessage());
            return back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('reservations.index')
            ->with('success', 'Reservasi berhasil dihapus');
    }

    /**
     * Generate unique booking number.
     */
    private function generateBookingNumber()
    {
        $prefix = 'BOOK';
        $year = date('Y');
        $month = date('m');
        
        $lastReservation = Reservation::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->latest()
            ->first();
        
        if ($lastReservation && $lastReservation->booking_no) {
            $lastNumber = intval(substr($lastReservation->booking_no, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return "{$prefix}/{$year}{$month}/{$newNumber}";
    }
}