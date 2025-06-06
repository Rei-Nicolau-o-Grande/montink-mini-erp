<div class="max-w-2xl mx-auto mt-10">
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <form
                class="space-y-4"
                action="{{ $action }}"
                method="{{ $method }}"
                {{ $attributes }}
            >
                {{ $slot }}
            </form>
        </div>
    </div>
</div>
