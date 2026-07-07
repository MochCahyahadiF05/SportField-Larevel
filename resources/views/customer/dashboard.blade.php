<h1>Dashboard Customer</h1>

<p>Selamat datang, {{ auth()->user()->name }}</p>

@include('customer.partials.toast')

@if (session('success'))
	<div style="margin-top: 1rem; padding: .75rem 1rem; border-radius: .75rem; background: #dcfce7; color: #166534;">
		{{ session('success') }}
	</div>
@endif
