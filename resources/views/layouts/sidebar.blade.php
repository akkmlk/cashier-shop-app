<aside class="main-sidebar sidebar-dark-orange elevation-4">
	<a href="/" class="brand-link">
		<img src="{{ asset('images/luminas.jpg') }}" alt="Luminas Cantik" class="brand-image img-circle elevation-3" style="opacity: .8">
		<span class="brand-text font-weight-height">{{ config('app.name') }}</span>
	</a>
	<div class="sidebar">
		<nav class="nav">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<x-nav-item title="Home" icon="fas fa-home" :routes="['home']" />
                @can('admin')
                    <x-nav-item title="User" icon="fas fa-user-tie" :routes="['user.index', 'user.create', 'user.edit']" />
                @endcan
                <x-nav-item title="Pelanggan" icon="fas fa-user" :routes="['pelanggan.index', 'pelanggan.create', 'pelanggan.edit']" />
                @can('admin')
                    <x-nav-item title="Kategori" icon="fas fa-list" :routes="['kategori.index', 'kategori.create', 'kategori.edit']" />
                @endcan
                <x-nav-item title="Promo" icon="fas fa-tags" :routes="['promo.index', 'promo.create', 'promo.edit']" />
                <x-nav-item title="Product" icon="fas fa-box-open" :routes="['product.index', 'product.create', 'product.edit']" />
                <x-nav-item title="Transaksi" icon="fas fa-cash-register" :routes="['transaksi.index', 'transaksi.create', 'transaksi.show']" />
                <x-nav-item title="Laporan" icon="fas fa-print" :routes="['laporan.index']" />
                <x-nav-item title="Stock" icon="fas fa-pallet" :routes="['stock.index', 'stock.create']" />
			</ul>
		</nav>
	</div>
</aside>