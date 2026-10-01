@extends('layouts.app')

@section('title', 'Chọn Ghế - HCTV')

@section('content')
<div class="pt-24 pb-12 bg-cinematic-dark min-h-screen">
    <div class="max-w-6xl mx-auto px-6 md:px-12">
        
        <!-- Header Info -->
        <div class="bg-white/5 border border-white/10 rounded-xl p-6 mb-8 flex flex-col md:flex-row justify-between items-center gap-4 shadow-lg">
            <div>
                <h2 class="text-2xl font-serif font-bold text-white">{{ $showtime->movie->title }}</h2>
                <p class="text-gray-400 mt-1">
                    {{ $showtime->room->cinema->name }} | {{ $showtime->room->name }} | {{ \Carbon\Carbon::parse($showtime->start_time)->format('d/m/Y - H:i') }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-400">Giá vé</p>
                <p class="text-xl font-bold text-cinematic-gold">{{ number_format($showtime->price, 0, ',', '.') }} VNĐ</p>
            </div>
        </div>

        @if(session('error'))
            <div class="bg-red-500/20 border border-red-500 text-red-100 px-4 py-3 rounded mb-8">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Seat Map -->
            <div class="flex-1 bg-white/5 border border-white/10 rounded-xl p-4 sm:p-6 md:p-8 shadow-xl flex flex-col overflow-hidden">
                <!-- Single Horizontal Scroll Container -->
                <div class="w-full overflow-x-auto pb-4" style="scrollbar-width: thin;">
                    <div class="w-max min-w-full flex flex-col items-center px-4">
                        
                        <!-- Screen Banner -->
                        <div class="seat-screen-container relative w-full max-w-md mx-auto mb-10 select-none">
                            <div class="seat-screen-beam h-2 w-full bg-gradient-to-r from-transparent via-white/40 to-transparent rounded-t-full shadow-[0_-10px_20px_rgba(255,255,255,0.3)] blur-[1px]"></div>
                            <div class="seat-screen-glow h-8 w-full bg-gradient-to-b from-white/15 to-transparent -mt-1 transform perspective-1000 rotateX-45"></div>
                            <p class="seat-screen-text text-center text-gray-300 text-xs sm:text-sm mt-3 uppercase tracking-widest font-bold">MÀN HÌNH CHIẾU</p>
                        </div>

                        <!-- Seats Grid Form -->
                        @php
                            $layoutRows = null;
                            if (!empty($showtime->room->layout_data)) {
                                $decoded = json_decode($showtime->room->layout_data, true);
                                if (is_array($decoded)) {
                                    $layoutRows = isset($decoded['grid']) ? $decoded['grid'] : (isset($decoded[0]) ? $decoded : null);
                                }
                            }

                            // Index seats by row and number
                            $seatMap = [];
                            foreach($showtime->room->seats as $s) {
                                $seatMap[$s->row . '_' . $s->number] = $s;
                            }

                            // Fallback rows grouping
                            $fallbackRows = [];
                            if (!$layoutRows) {
                                foreach($showtime->room->seats as $seat) {
                                    if(!isset($fallbackRows[$seat->row])) {
                                        $fallbackRows[$seat->row] = [];
                                    }
                                    $fallbackRows[$seat->row][] = $seat;
                                }
                            }
                        @endphp

                        <form id="seatsForm" action="{{ route('booking.process_seats', $showtime->id) }}" method="POST">
                            @csrf
                            <div class="flex flex-col items-center gap-2 sm:gap-2.5 py-2">
                                @if($layoutRows)
                                    @php
                                        $numCols = isset($layoutRows[0]) ? count($layoutRows[0]) : 16;
                                    @endphp
                                    <!-- Column Numbers Header -->
                                    <div class="flex items-center gap-1.5 sm:gap-2 mb-1 select-none">
                                        <div class="w-6 text-center text-xs font-bold text-gray-500"></div>
                                        <div class="flex gap-1.5 sm:gap-2">
                                            @for($c = 1; $c <= $numCols; $c++)
                                                <div class="w-7 sm:w-8 md:w-9 text-center text-[10px] sm:text-xs font-semibold text-gray-500">{{ $c }}</div>
                                            @endfor
                                        </div>
                                        <div class="w-6 text-center text-xs font-bold text-gray-500"></div>
                                    </div>

                                    @foreach($layoutRows as $rIdx => $rowCells)
                                        @php
                                            $rowLetter = chr(65 + $rIdx);
                                        @endphp
                                        <div class="flex items-center gap-1.5 sm:gap-2">
                                            <div class="w-6 text-center text-xs font-bold text-gray-400 select-none">{{ $rowLetter }}</div>
                                            <div class="flex gap-1.5 sm:gap-2">
                                                @foreach($rowCells as $cell)
                                                    @if(empty($cell) || $cell['type'] === 'aisle' || $cell['type'] === 'empty')
                                                        <div class="w-7 h-7 sm:w-8 sm:h-8 md:w-9 md:h-9"></div>
                                                    @else
                                                        @php
                                                            $key = ($cell['row'] ?? $rowLetter) . '_' . ($cell['number'] ?? '');
                                                            $seat = $seatMap[$key] ?? null;
                                                            $isBooked = $seat ? in_array($seat->id, $bookedSeatIds) : true;
                                                            $type = $cell['type'] ?? ($seat ? $seat->type : 'standard');
                                                            
                                                            // Pricing offset based on type
                                                            $seatPrice = $showtime->price;
                                                            if ($type === 'vip') $seatPrice += 20000;
                                                            elseif ($type === 'deluxe') $seatPrice += 30000;
                                                            elseif ($type === 'sweetbox') $seatPrice += 40000;

                                                            // Base styling
                                                            $typeColor = 'seat-standard bg-white/10 text-white border-white/20 hover:bg-white/25';
                                                            if ($type === 'vip') {
                                                                $typeColor = 'seat-vip bg-rose-950/70 text-rose-200 border-rose-500/70 hover:bg-rose-900/80 shadow-[0_0_8px_rgba(244,63,94,0.3)]';
                                                             } elseif ($type === 'sweetbox') {
                                                                 $typeColor = 'seat-sweetbox bg-sky-950/70 text-sky-200 border-sky-400/70 hover:bg-sky-900/80 shadow-[0_0_8px_rgba(56,189,248,0.3)]';
                                                             } elseif ($type === 'deluxe') {
                                                                 $typeColor = 'seat-deluxe bg-amber-950/70 text-amber-200 border-amber-400/70 hover:bg-amber-900/80 shadow-[0_0_8px_rgba(251,191,36,0.3)]';
                                                             }
                                                         @endphp

                                                         @if($seat)
                                                             <label class="relative cursor-pointer group select-none">
                                                                 <input type="checkbox" name="seats[]" value="{{ $seat->id }}" 
                                                                     class="peer sr-only seat-checkbox" 
                                                                     data-price="{{ $seatPrice }}" 
                                                                     data-label="{{ $seat->row }}{{ $seat->number }}" 
                                                                     {{ $isBooked ? 'disabled' : '' }}>
                                                                 <div class="seat-cell w-7 h-7 sm:w-8 sm:h-8 md:w-9 md:h-9 rounded-t-lg rounded-b-sm border flex items-center justify-center text-[10px] sm:text-xs font-semibold transition-all duration-200
                                                                     {{ $isBooked 
                                                                         ? '!bg-gray-800 !text-gray-600 !border-gray-700 cursor-not-allowed opacity-40 seat-booked' 
                                                                         : $typeColor . ' peer-checked:!bg-red-600 peer-checked:!text-white peer-checked:!border-red-500 peer-checked:shadow-[0_0_15px_rgba(229,9,20,0.8)]' }}">
                                                                     {{ $seat->number }}
                                                                 </div>
                                                             </label>
                                                         @else
                                                             <div class="w-7 h-7 sm:w-8 sm:h-8 md:w-9 md:h-9"></div>
                                                         @endif
                                                     @endif
                                                 @endforeach
                                             </div>
                                             <div class="w-6 text-center text-xs font-bold text-gray-400 select-none">{{ $rowLetter }}</div>
                                         </div>
                                     @endforeach
                                 @else
                                     @foreach($fallbackRows as $rowName => $seats)
                                         <div class="flex items-center gap-1.5 sm:gap-2">
                                             <div class="w-6 text-center font-bold text-gray-400 select-none">{{ $rowName }}</div>
                                             <div class="flex gap-1.5 sm:gap-2">
                                                 @foreach($seats as $seat)
                                                     @php
                                                         $isBooked = in_array($seat->id, $bookedSeatIds);
                                                     @endphp
                                                     <label class="relative cursor-pointer group select-none">
                                                         <input type="checkbox" name="seats[]" value="{{ $seat->id }}" class="peer sr-only seat-checkbox" data-price="{{ $showtime->price }}" data-label="{{ $seat->row }}{{ $seat->number }}" {{ $isBooked ? 'disabled' : '' }}>
                                                         <div class="seat-cell w-7 h-7 sm:w-8 sm:h-8 md:w-9 md:h-9 rounded-t-lg rounded-b-sm border border-white/20 flex items-center justify-center text-[10px] sm:text-xs font-semibold transition-all duration-200
                                                             {{ $isBooked 
                                                                 ? 'bg-gray-700 text-gray-500 cursor-not-allowed opacity-50 seat-booked' 
                                                                 : 'seat-standard bg-white/10 text-white hover:bg-white/30 peer-checked:bg-cinematic-red peer-checked:shadow-[0_0_15px_rgba(229,9,20,0.6)]' }}">
                                                             {{ $seat->number }}
                                                         </div>
                                                     </label>
                                                 @endforeach
                                             </div>
                                             <div class="w-6 text-center font-bold text-gray-400 select-none">{{ $rowName }}</div>
                                         </div>
                                     @endforeach
                                 @endif
                             </div>
                         </form>
                     </div>
                 </div>
                 
                 <!-- Legend -->
                 <div class="seat-legend flex flex-wrap justify-center items-center gap-5 sm:gap-6 mt-6 pt-6 border-t border-white/10">
                     <div class="flex items-center gap-2">
                         <div class="w-4 h-4 rounded-t bg-white/10 border border-white/20 legend-box-standard"></div>
                         <span class="text-xs text-gray-400 legend-text-standard">Standard</span>
                     </div>
                     <div class="flex items-center gap-2">
                         <div class="w-4 h-4 rounded-t bg-rose-950 border border-rose-500/70 shadow-[0_0_6px_rgba(244,63,94,0.3)]"></div>
                         <span class="text-xs text-rose-300 legend-text-vip">VIP (+20k)</span>
                     </div>
                     <div class="flex items-center gap-2">
                         <div class="w-4 h-4 rounded-t bg-sky-950 border border-sky-400/70 shadow-[0_0_6px_rgba(56,189,248,0.3)]"></div>
                         <span class="text-xs text-sky-300 legend-text-sweetbox">Sweetbox (+40k)</span>
                     </div>
                     <div class="flex items-center gap-2">
                         <div class="w-4 h-4 rounded-t bg-amber-950 border border-amber-400/70 shadow-[0_0_6px_rgba(251,191,36,0.3)]"></div>
                         <span class="text-xs text-amber-300 legend-text-deluxe">Deluxe (+30k)</span>
                     </div>
                     <div class="flex items-center gap-2">
                         <div class="w-4 h-4 rounded-t bg-red-600 shadow-[0_0_10px_rgba(229,9,20,0.7)]"></div>
                         <span class="text-xs text-white font-medium legend-text-selected">Đang chọn</span>
                     </div>
                     <div class="flex items-center gap-2">
                         <div class="w-4 h-4 rounded-t bg-gray-800 border border-gray-700 opacity-50 legend-box-booked"></div>
                         <span class="text-xs text-gray-500 legend-text-booked">Đã bán</span>
                     </div>
                 </div>
             </div>

             <!-- Summary Sidebar -->
             <div class="w-full lg:w-80 shrink-0">
                 <div class="bg-white/5 border border-white/10 rounded-xl p-6 shadow-xl sticky top-28">
                     <h3 class="text-xl font-bold text-white mb-6 border-b border-white/10 pb-4">Tóm tắt</h3>
                     
                     <div class="mb-4">
                         <p class="text-gray-400 text-sm">Ghế đã chọn:</p>
                         <p id="selectedSeatsLabel" class="text-white font-bold min-h-[1.5rem] mt-1 seat-summary-selected">-</p>
                    </div>
                    
                    <div class="mb-8">
                        <p class="text-gray-400 text-sm">Tổng tiền:</p>
                        <p id="totalPriceLabel" class="text-3xl font-bold text-cinematic-gold mt-1">0 VNĐ</p>
                    </div>
                    
                    <button type="button" id="btnContinue" class="w-full py-4 bg-cinematic-red text-white font-bold rounded shadow-[0_0_15px_rgba(229,9,20,0.4)] hover:bg-red-700 transition-colors uppercase tracking-wider">
                        Tiếp tục
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Real-time Conflict Alert Toast -->
<div id="seatConflictToast" class="fixed bottom-6 right-6 z-50 hidden max-w-md bg-amber-500/90 text-slate-950 font-bold px-5 py-4 rounded-xl shadow-2xl backdrop-blur-md border border-amber-300 transition-all duration-300 flex items-start gap-3">
    <svg class="w-6 h-6 shrink-0 text-slate-950 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    <div>
        <p class="text-xs uppercase tracking-wider font-black">Thông báo đồng bộ ghế</p>
        <p id="seatConflictToastText" class="text-sm font-semibold mt-0.5"></p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.seat-checkbox');
        const selectedSeatsLabel = document.getElementById('selectedSeatsLabel');
        const totalPriceLabel = document.getElementById('totalPriceLabel');
        const btnContinue = document.getElementById('btnContinue');
        const toast = document.getElementById('seatConflictToast');
        const toastText = document.getElementById('seatConflictToastText');
        let toastTimeout = null;

        function showToast(message) {
            toastText.textContent = message;
            toast.classList.remove('hidden');
            if (toastTimeout) clearTimeout(toastTimeout);
            toastTimeout = setTimeout(() => {
                toast.classList.add('hidden');
            }, 6000);
        }
        
        function updateSummary() {
            let selected = [];
            let total = 0;
            
            checkboxes.forEach(cb => {
                if (cb.checked && !cb.disabled) {
                    selected.push(cb.dataset.label);
                    total += parseFloat(cb.dataset.price);
                }
            });
            
            if (selected.length > 0) {
                selectedSeatsLabel.textContent = selected.join(', ');
                totalPriceLabel.textContent = new Intl.NumberFormat('vi-VN').format(total) + ' VNĐ';
            } else {
                selectedSeatsLabel.textContent = '-';
                totalPriceLabel.textContent = '0 VNĐ';
            }
        }
        
        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateSummary);
        });

        if (btnContinue) {
            btnContinue.addEventListener('click', function() {
                const checked = Array.from(checkboxes).filter(cb => cb.checked && !cb.disabled);
                if (checked.length === 0) {
                    alert('Vui lòng chọn ít nhất 1 ghế trước khi tiếp tục!');
                    return;
                }
                document.getElementById('seatsForm').submit();
            });
        }

        // Live Real-Time Polling: Synchronize seats with POS and other online bookings every 3 seconds
        const showtimeId = '{{ $showtime->id }}';
        function pollSeatStatus() {
            fetch(`{{ url('booking/seats') }}/${showtimeId}/status`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && Array.isArray(data.booked_seats)) {
                    const bookedIds = data.booked_seats.map(id => parseInt(id));

                    checkboxes.forEach(cb => {
                        const seatId = parseInt(cb.value);
                        const isBooked = bookedIds.includes(seatId);
                        const seatCell = cb.closest('label')?.querySelector('.seat-cell');

                        if (isBooked) {
                            if (cb.checked) {
                                cb.checked = false;
                                showToast(`Ghế ${cb.dataset.label} vừa được chọn/đặt tại quầy POS hoặc khách khác. Đã tự động bỏ chọn!`);
                                updateSummary();
                            }
                            cb.disabled = true;
                            if (seatCell) {
                                seatCell.classList.add('!bg-gray-800', '!text-gray-600', '!border-gray-700', 'cursor-not-allowed', 'opacity-40', 'seat-booked');
                            }
                        } else {
                            if (cb.disabled) {
                                cb.disabled = false;
                                if (seatCell) {
                                    seatCell.classList.remove('!bg-gray-800', '!text-gray-600', '!border-gray-700', 'cursor-not-allowed', 'opacity-40', 'seat-booked');
                                }
                            }
                        }
                    });
                }
            })
            .catch(err => console.error('Lỗi kiểm tra trạng thái ghế trực tiếp:', err));
        }

        // Poll every 3 seconds
        setInterval(pollSeatStatus, 3000);
    });
</script>
@endsection
