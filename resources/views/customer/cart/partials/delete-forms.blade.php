@foreach($cart as $item)
    <form id="delete-form-{{ $item['key'] }}" action="{{ route('customer.cart.destroy', $item['key']) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endforeach
