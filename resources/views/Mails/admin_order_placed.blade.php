Dear Admin,<br>
A new order has been placed by {{ $order->first_name }} {{ $order->last_name }} with {{ $order->user->email }} with the following details:<br>
@foreach ($cart as $item)
    Product Name: {{ $item->product->title }}<br>
    Price: {{ $item->product->newPrice }}$<br>
    Quantity: {{ $item->quantity }}<br>
    Color: {{ $item->product->color }}<br>
    Size: {{ $item->product->size }}<br>
    Category: {{ $item->product->category }}<br>
@endforeach

Total price: {{ $order->total }}$ EGP <br>
Payment Method: {{ $order->payment == 0 ? 'Paypal' : ($order->payment == 1 ? 'Cash' : 'Bank') }}<br>Address: {{ $order->address }}<br>Thank you for your attention.

Best regards






