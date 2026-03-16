<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Reservation Confirmation</title>

<style>

@page{
    size:A4;
    margin-top:2cm;
    margin-bottom:2cm;
    margin-left:2.5cm;
    margin-right:2.5cm;
}

body{
    font-family:"Times New Roman", Times, serif;
    font-size:10pt;
    line-height:1.25;
}

.container{
    width:100%;
}

.header{
    text-align:center;
    margin-bottom:8px;
}

.logo{
    width:70px;
}

.title{
    font-weight:bold;
    font-size:16pt;
}

.subtitle{
    font-weight:bold;
    margin-top:5px;
}

hr{
    border:1px solid black;
    margin:6px 0;
}

.table{
    width:100%;
    border-collapse:collapse;
}

.table td{
    padding:2px 4px;
    vertical-align:top;
}

.left{
    width:60%;
}

.right{
    width:40%;
}

.section{
    margin-top:6px;
    text-align:justify;
}

.policy{
    font-size:10pt;
}

.policy ol{
    margin:4px 0;
    padding-left:18px;
}

.footer-signature{
    margin-top:20px;
    text-align:right;
}

.signature-box{
    display:inline-block;
    width:220px;
    text-align:center;
}

.signature-line{
    border-bottom:1px solid black;
    margin-top:30px;
}

.signature-date{
    margin-top:6px;
}

.status-paid {
    color: green;
    font-weight: bold;
}

.status-partial {
    color: orange;
    font-weight: bold;
}

.status-refunded {
    color: red;
    font-weight: bold;
}

.status-unpaid {
    color: blue;
    font-weight: bold;
}

</style>
</head>

<body>

<div class="container">

<!-- HEADER -->
<div class="header">
<img src="{{ public_path('img/ppkdjp.jpg') }}" class="logo"><br>
<div class="title">PPKD HOTEL</div>
</div>

<div class="subtitle">Reservation Confirmation</div>

<hr>

<table class="table">

<!-- TO PALING ATAS -->
<tr>
<td colspan="2">
To : {{ $reservation->guest->name }}
</td>
</tr>

<!-- COMPANY SEJAJAR TELP -->
<tr>
<td class="left">
Company / Agent : {{ $reservation->company_agent ?? '-' }}
</td>

<td class="right">
Telp : {{ $reservation->agent_telp ?? '-' }}
</td>
</tr>

<tr>
    <td class="left">
        Booking No. : {{ $reservation->booking_no }} <br>
        Book By : {{ $reservation->book_by }} <br>
        Phone : {{ $reservation->guest->phone }} <br>
        Email : {{ $reservation->guest->email }}
        </td>

<td class="right">
Fax : {{ $reservation->agent_fax ?? '-' }} <br>
Email : {{ $reservation->agent_email ?? '-' }} <br>
Date : {{ date('d/m/Y') }}
</td>
</tr>

</table>

<hr>

<table class="table">

<tr>
<td width="180">First Name</td>
<td>: {{ $reservation->guest->name }}</td>
</tr>

<tr>
<td>Arrival Date</td>
<td>: {{ \Carbon\Carbon::parse($reservation->arrival_date)->format('d/m/Y') }}</td>
</tr>

<tr>
<td>Departure Date</td>
<td>: {{ \Carbon\Carbon::parse($reservation->departure_date)->format('d/m/Y') }}</td>
</tr>

<tr>
<td>Total Night</td>
<td>: {{ $reservation->total_nights }}</td>
</tr>

<tr>
<td>Room / Unit Type</td>
<td>: {{ $reservation->room_type }}</td>
</tr>

<tr>
<td>Person Pax</td>
<td>: {{ $reservation->number_of_persons }}</td>
</tr>

<tr>
<td>Room Rate Net</td>
<td>: Rp {{ number_format($reservation->room_rate_net,0,',','.') }}</td>
</tr>

<tr>
<td>Jumlah Kamar</td>
<td>: {{ $reservation->number_of_rooms }}</td>
</tr>

<tr>
<td>Total Harga</td>
<td>: Rp {{ number_format($reservation->room_rate_net * $reservation->total_nights * $reservation->number_of_rooms, 0, ',', '.') }}</td>
</tr>

</table>

<hr>

<div class="section">

@if($reservation->payment_method == 'Bank Transfer')
    <p>Please guarantee this booking with credit card number with clear copy of the card both sides and card holder signature in the column provided.  
    The copy of credit card both sides should be faxed to hotel fax number.</p>
    
    <p>Please settle your outstanding to our account:</p>

    <br>
    
    <b>Bank Transfer</b><br>
    Mandiri Account : {{ $reservation->bank_account ?? '-' }} <br>
    Mandiri Name Account : {{ $reservation->bank_account_name ?? '-' }} <br>
    
    @if(isset($reservation->payment_status) && $reservation->payment_status != '')
        <br>
        <b>Status Pembayaran:</b> 
        @if($reservation->payment_status == 'paid')
            <span class="status-paid">Lunas</span>
        @elseif($reservation->payment_status == 'partial')
            <span class="status-partial">Dibayar Sebagian</span>
        @elseif($reservation->payment_status == 'refunded')
            <span class="status-refunded">Dikembalikan</span>
        @else
            <span class="status-unpaid">Belum Dibayar</span>
        @endif
    @endif
    
    @if(isset($reservation->payment_notes) && $reservation->payment_notes != '')
        <br>
        <b>Catatan Pembayaran:</b> {{ $reservation->payment_notes }}
    @endif
    
@elseif($reservation->payment_method == 'Credit Card')
    <p>Please guarantee this booking with credit card number with clear copy of the card both sides and card holder signature in the column provided.  
    The copy of credit card both sides should be faxed to hotel fax number.</p>
    
    <br>
    
    <b>Credit Card Information:</b><br>
    Card Number : {{ $reservation->masked_cc_number ?? $reservation->cc_number }} <br>
    Card holder name : {{ $reservation->cc_holder_name ?? '-' }} <br>
    Card Type : {{ $reservation->cc_type ?? '-' }} <br>
    Payment Method : {{ $reservation->payment_method }} <br>
    Expired date : {{ $reservation->cc_expired ?? '-' }} <br>
    
    @if(isset($reservation->payment_status) && $reservation->payment_status != '')
        <br>
        <b>Status Pembayaran:</b> 
        @if($reservation->payment_status == 'paid')
            <span class="status-paid">Lunas</span>
        @elseif($reservation->payment_status == 'partial')
            <span class="status-partial">Dibayar Sebagian</span>
        @elseif($reservation->payment_status == 'refunded')
            <span class="status-refunded">Dikembalikan</span>
        @else
            <span class="status-unpaid">Belum Dibayar</span>
        @endif
    @endif
    
    @if(isset($reservation->payment_notes) && $reservation->payment_notes != '')
        <br>
        <b>Catatan Pembayaran:</b> {{ $reservation->payment_notes }}
    @endif
    
@elseif($reservation->payment_method == 'Cash')
    <p><b>Pembayaran Tunai (Cash)</b></p>
    <p>Pembayaran akan dilakukan secara tunai saat check-in di hotel.</p>
    <p>Harap siapkan uang tunai sesuai dengan total tagihan Anda.</p>
    
    <br>
    
    <b>Detail Pembayaran Tunai:</b><br>
    Total Pembayaran : Rp {{ number_format($reservation->room_rate_net * $reservation->total_nights * $reservation->number_of_rooms, 0, ',', '.') }} <br>
    Metode Pembayaran : Tunai (Cash) <br>
    
    @if(isset($reservation->payment_status) && $reservation->payment_status != '')
        Status Pembayaran : 
        @if($reservation->payment_status == 'paid')
            <span class="status-paid">Lunas</span>
        @elseif($reservation->payment_status == 'partial')
            <span class="status-partial">Dibayar Sebagian</span>
        @elseif($reservation->payment_status == 'refunded')
            <span class="status-refunded">Dikembalikan</span>
        @else
            <span class="status-unpaid">Belum Dibayar (akan dibayar saat check-in)</span>
        @endif
        <br>
    @else
        Status Pembayaran : <span class="status-unpaid">Belum Dibayar (akan dibayar saat check-in)</span><br>
    @endif
    
    @if(isset($reservation->payment_notes) && $reservation->payment_notes != '')
        <br>
        <b>Catatan Pembayaran:</b><br>
        {{ $reservation->payment_notes }} <br>
    @endif
    
    <br>
    <b>Rincian Harga:</b><br>
    Harga per Malam : Rp {{ number_format($reservation->room_rate_net, 0, ',', '.') }} <br>
    Jumlah Kamar : {{ $reservation->number_of_rooms }} kamar <br>
    Jumlah Malam : {{ $reservation->total_nights }} malam <br>
    <b>Total Keseluruhan : Rp {{ number_format($reservation->room_rate_net * $reservation->total_nights * $reservation->number_of_rooms, 0, ',', '.') }}</b>
    
@else
    <p>Please guarantee this booking with credit card number with clear copy of the card both sides and card holder signature in the column provided.  
    The copy of credit card both sides should be faxed to hotel fax number.</p>
    
    <p>Please settle your outstanding to our account:</p>

    <br>
    
    <b>Bank Transfer</b><br>
    Mandiri Account : {{ $reservation->bank_account ?? '-' }} <br>
    Mandiri Name Account : {{ $reservation->bank_account_name ?? '-' }} <br>
    
    @if(isset($reservation->payment_status) && $reservation->payment_status != '')
        <br>
        <b>Status Pembayaran:</b> 
        @if($reservation->payment_status == 'paid')
            <span class="status-paid">Lunas</span>
        @elseif($reservation->payment_status == 'partial')
            <span class="status-partial">Dibayar Sebagian</span>
        @elseif($reservation->payment_status == 'refunded')
            <span class="status-refunded">Dikembalikan</span>
        @else
            <span class="status-unpaid">Belum Dibayar</span>
        @endif
    @endif
@endif

</div>

<hr>

@if($reservation->payment_method == 'Credit Card')
<div class="section">

<b>Reservation guaranteed by the following credit card:</b>

<br><br>

Card Number : {{ $reservation->masked_cc_number ?? $reservation->cc_number }} <br>
Card holder name : {{ $reservation->cc_holder_name }} <br>
Card Type : {{ $reservation->cc_type }} <br>
Payment Method : {{ $reservation->payment_method }} <br>
Expired date / month / year : {{ $reservation->cc_expired }} <br>
Card holder signature : ___________________________________

</div>

<hr>
@endif

<div class="policy">

<b>Cancellation policy:</b>

<ol>
<li>Please note that check in time is 02.00 pm and check out time 12.00 pm.</li>
<li>All non guaranteed reservations will automatically be released on 6 pm.</li>
<li>The Hotel will charge 1 night for guaranteed reservations that have not been canceled before the day of arrival.</li>
</ol>

</div>

<div class="footer-signature">

<div class="signature-box">

Guest Signature
<br>
<br>
<br>
<div class="signature-line"></div>

<div class="signature-date">
Date : {{ \Carbon\Carbon::parse($reservation->created_at)->format('d/m/Y') }}
</div>

</div>

</div>

</div>

</body>
</html>