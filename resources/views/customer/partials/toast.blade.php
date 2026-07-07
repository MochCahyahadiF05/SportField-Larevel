@php
    $toastMessage = session('success') ?? session('error');
    $toastType = session('success') ? 'success' : (session('error') ? 'error' : null);
@endphp

@if ($toastMessage)
    <div id="sfToast" class="sf-toast sf-toast-{{ $toastType }}" role="status" aria-live="polite">
        <div class="sf-toast-icon">
            @if ($toastType === 'success')
                <i class="fa-solid fa-circle-check"></i>
            @else
                <i class="fa-solid fa-circle-exclamation"></i>
            @endif
        </div>
        <div class="sf-toast-body">
            <strong>{{ $toastType === 'success' ? 'Berhasil' : 'Gagal' }}</strong>
            <p>{{ $toastMessage }}</p>
        </div>
        <button type="button" class="sf-toast-close" aria-label="Tutup toast" onclick="document.getElementById('sfToast')?.remove()">&times;</button>
    </div>

    <style>
        .sf-toast {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            z-index: 9999;
            display: flex;
            align-items: flex-start;
            gap: .85rem;
            min-width: 320px;
            max-width: 420px;
            padding: 1rem 1.1rem;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 18px 35px rgba(15, 23, 42, .14);
            border: 1px solid #e5e7eb;
            animation: sfToastIn .22s ease-out;
        }

        .sf-toast-success { border-left: 5px solid #16a34a; }
        .sf-toast-error { border-left: 5px solid #ef4444; }
        .sf-toast-icon {
            width: 2rem;
            height: 2rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .sf-toast-success .sf-toast-icon { background: #dcfce7; color: #16a34a; }
        .sf-toast-error .sf-toast-icon { background: #fee2e2; color: #ef4444; }
        .sf-toast-body strong { display:block; color:#0f172a; margin-bottom: .15rem; }
        .sf-toast-body p { margin: 0; color:#475569; font-size: .92rem; line-height: 1.45; }
        .sf-toast-close {
            margin-left: auto;
            border: 0;
            background: transparent;
            color: #94a3b8;
            font-size: 1.5rem;
            line-height: 1;
            cursor: pointer;
            padding: 0 .15rem;
        }
        @keyframes sfToastIn {
            from { opacity: 0; transform: translateY(-8px) translateX(8px); }
            to { opacity: 1; transform: translateY(0) translateX(0); }
        }
        @media (max-width: 640px) {
            .sf-toast { left: 1rem; right: 1rem; min-width: 0; max-width: none; }
        }
    </style>

    <script>
        setTimeout(function () {
            const toast = document.getElementById('sfToast');
            if (toast) {
                toast.remove();
            }
        }, 3000);
    </script>
@endif