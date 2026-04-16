{{-- <div class="flex w-full flex-col">
        <div class="divider divider-neutral text-lg sm:text-2xl font-bold text-green-900 text-2xl m-10">
        {{ $slot }}
    </div>
</div> --}}


<style>
    .cement-divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 3rem 0;
    }
    .cement-divider::before, .cement-divider::after {
        content: '';
        flex: 1;
        height: 4px;
        border-radius: 2px;
    }
    .cement-divider::before {
        background: linear-gradient(to left, #006b36, transparent);
    }
    .cement-divider::after {
        background: linear-gradient(to right, #006b36, transparent);
    }
    .divider-content {
        padding: 0 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .divider-tag {
        background: #000;
        color: #006b36;
        font-size: 0.7rem;
        padding: 2px 12px;
        border-radius: 50px;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 2px;
    }
</style>

<div class="cement-divider">
    <div class="divider-content">
        <!-- <span class="divider-tag">{{ __('adminlte::landingpage.ArabCementCompany') }}</span> -->
        <h2 class="text-2xl sm:text-3xl font-black text-slate-800">{{ $slot }}</h2>
    </div>
</div>





