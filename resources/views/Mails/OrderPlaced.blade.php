Dear {{ $order->first_name }},<br>
@foreach ($cart as $item)
    Product Name: {{ $item->product->title }}<br>
    Price: {{ $item->product->newPrice }}$<br>

@endforeach
Total price: {{ $order->total }}$ EGP <br>
Payment Method: {{ $order->payment == 0 ? 'Paypal' : ($order->payment == 1 ? 'Cash' : 'Bank') }}<br>Address: {{ $order->address }}<br>Address: {{ $order->address }}<br>
Phone: {{ $order->phone }}<br>
Shiping Cost: {{ $order->shipingCost }}$<br>
Thanks again for your order!

