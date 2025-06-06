@props([
    'label' => '',
    'type' => 'text',
    'id' => '',
    'name' => '',
    'placeholder' => '',
    'value' => '',
    'autocomplete' => '',
    'required' => false,
    'error' => null,
    'disabled' => false
])

<div class="form-control mb-4">
    <label
        for="{{ $id }}"
        class="label"
    >
        <span class="label-text font-bold">{{ $label }}</span>
    </label>
    <input
        type="{{ $type }}"
        id="{{ $id }}"
        placeholder="{{ $placeholder }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        autocomplete="{{ $autocomplete }}"
        class="input input-bordered w-full {{ $error ? 'input-error' : '' }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes }}
    >
    @if(is_array($error))
        @foreach($error as $err)
            <p class="text-error mt-1">{{ $err }}</p>
        @endforeach
    @elseif($error)
        <p class="text-error mt-1">{{ $error }}</p>
    @endif
</div>
